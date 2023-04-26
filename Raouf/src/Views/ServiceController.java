/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package Views;

import Entities.Remorquage;
import Entities.Service;
import Interface.IRemorquageService;
import Interface.IServiceService;
import MyConnection.MyConnection;
import Services.RemorquageService;
import Services.ServiceService;
import java.net.URL;
import java.sql.Connection;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.sql.Statement;
import java.util.List;
import java.util.Optional;
import java.util.ResourceBundle;
import javafx.beans.property.SimpleObjectProperty;
import javafx.beans.property.SimpleStringProperty;
import javafx.collections.FXCollections;
import javafx.collections.ObservableList;
import javafx.fxml.FXML;
import javafx.fxml.Initializable;
import javafx.scene.control.Alert;
import javafx.scene.control.Alert.AlertType;
import javafx.scene.control.Button;
import javafx.scene.control.ButtonType;
import javafx.scene.control.TableColumn;
import javafx.scene.control.TableView;
import javafx.scene.control.TextArea;
import javafx.scene.input.MouseEvent;
import javafx.stage.Stage;

/**
 * FXML Controller class
 *
 * @author TECHN
 */
public class ServiceController implements Initializable {

    @FXML
    private TableView<Service> tableService;
    @FXML
    private TableColumn<Service, Integer> id;
    @FXML
    private Button fermerService;
    @FXML
    private TextArea libelleserviceService;
    @FXML
    private TextArea nomserviceService;
    @FXML
    private TextArea idService;
    @FXML
    private Button ajouterService;
    @FXML
    private Button supprimerService;
    @FXML
    private Button modifierService;
    @FXML
    private TableColumn<Service, String> libelle;
    @FXML
    private TableColumn<Service, String> nom;
    
    
    Connection mc;
    PreparedStatement ste;
    ObservableList<Service>ServiceList;
    

    /**
     * Initializes the controller class.
     */
   
    
     
    
    
    
    
    
    
    
    @Override
    public void initialize(URL url, ResourceBundle rb) {
         afficherServices();
    }    

   
    void afficherServices(){
            mc=MyConnection.getInstance().getCnx();
            ServiceList = FXCollections.observableArrayList();
      
      try {
            String requete = "SELECT * FROM service s ";
            Statement st = MyConnection.getInstance().getCnx()
                    .createStatement();
            ResultSet rs =  st.executeQuery(requete); 
            while(rs.next()){
                Service s = new Service();
                s.setId(    rs.getInt("id"));
                s.setLibelleService(rs.getString("libelleService"));
                s.setNomService(rs.getString("nomService"));
                ServiceList.add(s);
            }
        } catch (SQLException ex) {
            System.out.println(ex.getMessage());
        }
  id.setCellValueFactory(data -> new SimpleObjectProperty(data.getValue().getId()));
  libelle.setCellValueFactory(data -> new SimpleStringProperty(data.getValue().getLibelleService()));
  nom.setCellValueFactory(data -> new SimpleStringProperty(data.getValue().getNomService()));
  tableService.setItems(ServiceList);
  
  refresh();
  
  }
    
    
    
    
    
    
    public void refresh(){
            ServiceList.clear();
            mc=MyConnection.getInstance().getCnx();
            ServiceList = FXCollections.observableArrayList();
      
      try {
            String requete = "SELECT * FROM service s ";
            Statement st = MyConnection.getInstance().getCnx()
                    .createStatement();
            ResultSet rs =  st.executeQuery(requete); 
            while(rs.next()){
                 Service s = new Service();
               
                 s.setId(rs.getInt("id"));
                s.setLibelleService(rs.getString("libelleService"));
                s.setNomService(rs.getString("nomService"));
                
               
                
                ServiceList.add(s);
            }
        } catch (SQLException ex) {
            System.out.println(ex.getMessage());
        }
        tableService.setItems(ServiceList);
      
  }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    @FXML
    private void selected(MouseEvent event) {
        
         Service clicked = tableService.getSelectionModel().getSelectedItem();
        idService.setText(String.valueOf(clicked.getId()));
        libelleserviceService.setText(String.valueOf(clicked.getLibelleService()));
        nomserviceService.setText(String.valueOf(clicked.getNomService()));
       
        
    }

    
    
    
    @FXML
    private void closeService(MouseEvent event) {
        Stage stage =(Stage) fermerService.getScene().getWindow();
        stage.close(); 
    }

   
    
    
@FXML
private void addService(MouseEvent event) {
    String libelle = libelleserviceService.getText().trim();
    String nom = nomserviceService.getText().trim();

    if (libelle.isEmpty() || nom.isEmpty()) {
        Alert alert = new Alert(AlertType.WARNING);
        alert.setTitle("Champs vides");
        alert.setHeaderText("Veuillez remplir tous les champs");
        alert.showAndWait();
    } 
    
    else if (!libelle.matches("^[a-zA-Z]+$") || !nom.matches("^[a-zA-Z]+$")) {
        
        Alert alert = new Alert(AlertType.WARNING);
        alert.setTitle("Caractères invalides");
        alert.setHeaderText("Les champs ne doivent contenir que des lettres");
        alert.showAndWait();
    } else 
    {
        Service s = new Service(1, libelle, nom);

        IServiceService ss = new ServiceService();
        ss.ajouterService(s);

        idService.setText(null);
        libelleserviceService.setText(null);
        nomserviceService.setText(null);
        refresh();
    }
}



    
    
    
    
    
    
    
    
    
@FXML
private void deleteService(MouseEvent event) {
    Alert alert = new Alert(Alert.AlertType.CONFIRMATION);
    alert.setHeaderText("Warning");
    alert.setContentText("Es-tu sûre de supprimer!");

    String value1 = idService.getText();
    String libelle = libelleserviceService.getText();
    String nom = nomserviceService.getText();

    Optional<ButtonType> result = alert.showAndWait();
    if (result.get() == ButtonType.OK) {
        // Vérifier si le service est utilisé dans un remorquage
        IRemorquageService rs = new RemorquageService();
        List<Remorquage> remorquages = rs.getRemorquagesByService(Integer.parseInt(value1));
        if (!remorquages.isEmpty()) {
            Alert confirmAlert = new Alert(Alert.AlertType.WARNING);
            confirmAlert.setHeaderText("Attention");
            confirmAlert.setContentText("Le service est utilisé dans un ou plusieurs remorquages. Il ne peut pas être supprimé.");
            confirmAlert.showAndWait();
            return;
        }
        else{
        // Supprimer le service
        IServiceService ss = new ServiceService();
        ss.supprimerService(new Service(Integer.parseInt(value1), libelle, nom));
        refresh();
       
        idService.setText(null);
        libelleserviceService.setText(null);
        nomserviceService.setText(null);
        refresh();
    }
}   

}





    

@FXML
    private void updateService(MouseEvent event) {
        
        Alert alert = new Alert(Alert.AlertType.CONFIRMATION);
            alert.setHeaderText("Warning");
            alert.setContentText("Es-tu sûre de modifier!");
        
            
            
        String Value1 = idService.getText();
        String libelle =libelleserviceService.getText();
        String nom =nomserviceService.getText();
      
         
         Optional<ButtonType>result =  alert.showAndWait();
        if(result.get() == ButtonType.OK)
        {
      
         Service s= new Service(Integer.parseInt(Value1),libelle,nom);
        IServiceService ss= new ServiceService();
        ss.modifierService(s);
        
        refresh();
      
        idService.setText(null);
        libelleserviceService.setText(null);
        nomserviceService.setText(null);
        
        }
        
        
        
        
        
        
        
    }
    
}
