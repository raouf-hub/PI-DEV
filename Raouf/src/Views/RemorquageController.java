/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package Views;

import API.EnvoyerEmail;
import Entities.Remorquage;
import Interface.IRemorquageService;
import MyConnection.MyConnection;
import Services.RemorquageService;
import com.itextpdf.text.Document;
import com.itextpdf.text.DocumentException;
import com.itextpdf.text.Element;
import com.itextpdf.text.FontFactory;
import com.itextpdf.text.Paragraph;
import com.itextpdf.text.Phrase;
import com.itextpdf.text.pdf.PdfPCell;
import com.itextpdf.text.pdf.PdfPTable;
import com.itextpdf.text.pdf.PdfWriter;
import java.awt.Desktop;
import java.io.File;
import java.io.FileNotFoundException;
import java.io.FileOutputStream;
import java.io.IOException;
import static java.lang.Boolean.parseBoolean;
import java.net.URL;
import java.sql.Connection;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.sql.Statement;
import java.util.ArrayList;
import java.util.List;
import java.util.Optional;
import java.util.Random;
import java.util.ResourceBundle;
import java.util.logging.Level;
import java.util.logging.Logger;
import javafx.beans.property.SimpleBooleanProperty;
import javafx.beans.property.SimpleObjectProperty;
import javafx.beans.property.SimpleStringProperty;
import javafx.collections.FXCollections;
import javafx.collections.ObservableList;
import javafx.collections.transformation.FilteredList;
import javafx.collections.transformation.SortedList;
import javafx.fxml.FXML;
import javafx.fxml.Initializable;
import javafx.scene.control.Alert;
import javafx.scene.control.Button;
import javafx.scene.control.ButtonType;
import javafx.scene.control.ComboBox;
import javafx.scene.control.TableColumn;
import javafx.scene.control.TableView;
import javafx.scene.control.TextArea;
import javafx.scene.input.MouseEvent;
import javafx.stage.Stage;
import javax.mail.MessagingException;
import org.controlsfx.control.Notifications;

/**
 * FXML Controller class
 *
 * @author TECHN
 */
public class RemorquageController implements Initializable {

    @FXML
    private TableView<Remorquage> tableRemorquage;
    @FXML
    private TableColumn<Remorquage, Integer> id;
    @FXML
    private TableColumn<Remorquage, String> nom;
    @FXML
    private TableColumn<Remorquage, String> prenom;
    @FXML
    private TableColumn<Remorquage, String> email;
    @FXML
    private TableColumn<Remorquage, String> numtel;
    @FXML
    private TextArea idremorquage;
    @FXML
    private TextArea nomremorquage;
    @FXML
    private TextArea prenomremorquage;
    @FXML
    private TextArea emailremorquage;
    @FXML
    private TextArea numtelremorquage;
    @FXML
    private ComboBox<Integer> idservice;
    @FXML
    private Button ajouterRom;
    @FXML
    private Button supprimerRom;
    @FXML
    private Button modifierRom;
     
    
    Connection mc;
    PreparedStatement ste;
    ObservableList<Remorquage>remList;
    @FXML
    private TableColumn<Remorquage, Integer > ids;
    @FXML
    private TextArea rechercher;
    @FXML
    private Button fermerRemorquage;
    @FXML
    private Button pdfbtn;
    
    
    
    
    
    /**
     * Initializes the controller class.
     */
   
    
    
    public int getNombreRemorquages(String nom, String prenom) {
    int nombreRemorquages = 0;
    try {
        String requete = "SELECT COUNT(*) as nbRemorquages FROM remorquage WHERE name=? AND prenom=?";
        PreparedStatement ps = MyConnection.getInstance().getCnx().prepareStatement(requete);
        ps.setString(1, nom);
        ps.setString(2, prenom);
        ResultSet rs = ps.executeQuery();
        if (rs.next()) {
            nombreRemorquages = rs.getInt("nbRemorquages");
        }
    } catch (SQLException ex) {
        System.out.println(ex.getMessage());
    }
    return nombreRemorquages;
}

    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    @Override
    public void initialize(URL url, ResourceBundle rb) {
      try {
            String req="select id from service";
            PreparedStatement pst = MyConnection.getInstance().getCnx()
                    .prepareStatement(req);
            ResultSet rs=pst.executeQuery();
            ObservableList<Integer> id = null;
            List<Integer> list = new ArrayList<>();
            while(rs.next()){
                
                list.add(rs.getInt("id"));
                
            }
            id = FXCollections
                    .observableArrayList(list);
            idservice.setItems(id);
        } catch (SQLException ex) {
            Logger.getLogger(RemorquageController.class.getName()).log(Level.SEVERE, null, ex);
        }
        
        afficherRemorquages();
    }    

    
    
    
    
     void afficherRemorquages(){
            mc=MyConnection.getInstance().getCnx();
            remList = FXCollections.observableArrayList();
      
      try {
            String requete = "SELECT * FROM remorquage r ";
            Statement st = MyConnection.getInstance().getCnx()
                    .createStatement();
            ResultSet rs =  st.executeQuery(requete); 
            while(rs.next()){
                Remorquage r = new Remorquage();
                r.setIdremorquage(rs.getInt("idremorquage"));
                r.setIds(rs.getInt("ids"));
                r.setName(rs.getString("name"));
                r.setPrenom(rs.getString("prenom"));
                r.setEmail(rs.getString("email"));
                r.setNumtel(rs.getInt("numtel"));
                
                remList.add(r);
            }
        } catch (SQLException ex) {
            System.out.println(ex.getMessage());
        }
  id.setCellValueFactory(data -> new SimpleObjectProperty(data.getValue().getIdremorquage()));
  ids.setCellValueFactory(data -> new SimpleObjectProperty(data.getValue().getIds()));
  nom.setCellValueFactory(data -> new SimpleStringProperty(data.getValue().getName()));
  prenom.setCellValueFactory(data -> new SimpleStringProperty(data.getValue().getPrenom()));
  email.setCellValueFactory(data -> new SimpleStringProperty(data.getValue().getEmail()));
  numtel.setCellValueFactory(data -> new SimpleObjectProperty(data.getValue().getNumtel()));
 
   tableRemorquage.setItems(remList);
  
   refresh();
   rechercher();
  }
  
  
    
    public void refresh(){
            remList.clear();
            mc=MyConnection.getInstance().getCnx();
            remList = FXCollections.observableArrayList();
      
      try {
            String requete = "SELECT * FROM remorquage r ";
            Statement st = MyConnection.getInstance().getCnx()
                    .createStatement();
            ResultSet rs =  st.executeQuery(requete); 
            while(rs.next()){
                Remorquage r = new Remorquage();
                
                r.setIdremorquage(rs.getInt("idremorquage"));
                r.setIds(rs.getInt("ids"));
                r.setName(rs.getString("name"));
                r.setPrenom(rs.getString("prenom"));
                r.setEmail(rs.getString("email"));
                r.setNumtel(rs.getInt("numtel"));
                remList.add(r);
            }
        } catch (SQLException ex) {
            System.out.println(ex.getMessage());
        }
        tableRemorquage.setItems(remList);
       rechercher();
  }
    
   
    
    
    
    
    
    @FXML
    private void selected(MouseEvent event) {
        
         Remorquage clicked = tableRemorquage.getSelectionModel().getSelectedItem();
         
         idremorquage.setText(String.valueOf(clicked.getIdremorquage()));
         idservice.setValue(clicked.getIds());
        nomremorquage.setText(String.valueOf(clicked.getName()));
        prenomremorquage.setText(String.valueOf(clicked.getPrenom()));
        emailremorquage.setText(String.valueOf(clicked.getEmail()));
        numtelremorquage.setText(String.valueOf(clicked.getNumtel()));
        
        
    }

  @FXML
private void addRom(MouseEvent event) throws MessagingException {
 
    
    String idser = idservice.getValue() != null ? idservice.getValue().toString() : "";   //? maaneha bch tcompari b NULL
    String nom = nomremorquage.getText();
    String prenom = prenomremorquage.getText();
    String email = emailremorquage.getText();
    String numtel = numtelremorquage.getText();
    
    if (nom.isEmpty() || prenom.isEmpty() || email.isEmpty() || numtel.isEmpty()  || idser.isEmpty() ) {
        Alert alert = new Alert(Alert.AlertType.WARNING);
        alert.setTitle("Champs vides");
        alert.setHeaderText(null);
        alert.setContentText("Veuillez remplir tous les champs !");
        alert.showAndWait();
    }
    else if(!nom.matches("^[a-zA-Z]+$") || !prenom.matches("^[a-zA-Z]+$")){
    
     Alert alert = new Alert(Alert.AlertType.WARNING);
        alert.setTitle("Caractères invalides");
        alert.setHeaderText("Les champs ne doivent contenir que des lettres");
        alert.showAndWait();
    }
    
    else if (numtel.isEmpty() || !numtel.matches("^[0-9]{8}$")) {
    Alert alert = new Alert(Alert.AlertType.WARNING);
    alert.setTitle("Caractères invalides");
    alert.setHeaderText("Le champ 'numéro de téléphone' doit contenir 8 chiffres");
    alert.showAndWait();
    }
    
    else if (!email.matches("^\\S+@\\S+\\.\\S+$")) {
        
    Alert alert = new Alert(Alert.AlertType.WARNING);
    alert.setTitle("Caractères invalides");
    alert.setHeaderText("L'adresse email saisie n'est pas valide");
    alert.showAndWait();
    }
 
    else if(getNombreRemorquages(nom,prenom)>=2){
    
    Alert alert = new Alert(Alert.AlertType.WARNING);
    alert.setTitle("nom et prenom ont déja utilisé un remorquage");
    alert.setHeaderText("Tu as le droit ajouter deux remorquages.Tu reçois un email pour plus de détails");
    alert.showAndWait();
     
    
    
     String destinataire = "raouf.chracheri@esprit.tn";
     int code = new Random().nextInt(99999);
     EnvoyerEmail.envoyer(destinataire, code);
    }
    
    
    else {
        Remorquage r = new Remorquage(Integer.parseInt(idser), nom, prenom, email, Integer.parseInt(numtel));
        IRemorquageService rs = new RemorquageService();
        rs.ajouterRemorquage(r);
        Notifications.create().title("Success").text("Le remorquage est ajouté avec succes").showInformation();//notificaation
        refresh();
        
        idservice.setValue(null);
        nomremorquage.setText("");
        prenomremorquage.setText("");
        emailremorquage.setText("");
        numtelremorquage.setText("");
    }
}


    



@FXML
    private void deleteRom(MouseEvent event) {
        
        Alert alert = new Alert(Alert.AlertType.CONFIRMATION);
       alert.setHeaderText("Warning");
       alert.setContentText("Es-tu sûre de supprimer!"); 
        
        
        String idmorq  = idremorquage.getText();
        String idser = idservice.getSelectionModel().getSelectedItem().toString();
        String nom =nomremorquage.getText();
        String prenom =prenomremorquage.getText();
        String email =emailremorquage.getText();
        String numtel =numtelremorquage.getText();
        
        
         Optional<ButtonType>result =  alert.showAndWait();
        if(result.get() == ButtonType.OK)
        {
        
        Remorquage r= new Remorquage(Integer.parseInt(idmorq),Integer.parseInt(idser),nom,prenom,email,Integer.parseInt(numtel));
        IRemorquageService rs= new RemorquageService();
        rs.supprimerRemorquage(r);
       
        refresh();
        
        
        idservice.setValue(null);    
        nomremorquage.setText(null);
        prenomremorquage.setText(null);
        emailremorquage.setText(null);
        numtelremorquage.setText(null); 
       }
        
        
        
        
        
    }

    @FXML
    private void updateRom(MouseEvent event) {
         Alert alert = new Alert(Alert.AlertType.CONFIRMATION);
       alert.setHeaderText("Warning");
       alert.setContentText("vérifier vos modifications !"); 
        
        
        String idmorq  = idremorquage.getText();
        String idser = idservice.getSelectionModel().getSelectedItem().toString();
        String nom =nomremorquage.getText();
        String prenom =prenomremorquage.getText();
        String email =emailremorquage.getText();
        String numtel =numtelremorquage.getText();
        
        
         Optional<ButtonType>result =  alert.showAndWait();
        if(result.get() == ButtonType.OK)
        {
            
            
             if (nom.isEmpty() || prenom.isEmpty() || email.isEmpty() || numtel.isEmpty()  || idser.isEmpty() ) {
        Alert alert1 = new Alert(Alert.AlertType.WARNING);
        alert.setTitle("Champs vides");
        alert.setHeaderText(null);
        alert.setContentText("Veuillez remplir tous les champs !");
        alert.showAndWait();
    }
    else if(!nom.matches("^[a-zA-Z]+$") || !prenom.matches("^[a-zA-Z]+$")){
    
     Alert alert2 = new Alert(Alert.AlertType.WARNING);
        alert.setTitle("Caractères invalides");
        alert.setHeaderText("Les champs ne doivent contenir que des lettres");
        alert.showAndWait();
    }
    
    else if (numtel.isEmpty() || !numtel.matches("^[0-9]{8}$")) {
    Alert alert3 = new Alert(Alert.AlertType.WARNING);
    alert.setTitle("Caractères invalides");
    alert.setHeaderText("Le champ 'numéro de téléphone' doit contenir 8 chiffres");
    alert.showAndWait();
    }
    
    else if (!email.matches("^\\S+@\\S+\\.\\S+$")) {
        
    Alert alert4 = new Alert(Alert.AlertType.WARNING);
    alert.setTitle("Caractères invalides");
    alert.setHeaderText("L'adresse email saisie n'est pas valide");
    alert.showAndWait();
    }
    else{
        
        Remorquage r= new Remorquage(Integer.parseInt(idmorq),Integer.parseInt(idser),nom,prenom,email,Integer.parseInt(numtel));
        IRemorquageService rs= new RemorquageService();
        rs.modifierRemorquage(r);
       
        refresh();
        
        
        idservice.setValue(null);
        nomremorquage.setText(null);
        prenomremorquage.setText(null);
        emailremorquage.setText(null);
        numtelremorquage.setText(null); 
       }
        
        
    }
    
    }
        
        
        
        
   
         
         
         
         @FXML
    private void closeService(MouseEvent event) {
        Stage stage =(Stage) fermerRemorquage.getScene().getWindow();
        stage.close(); 
    }
        
        
        
      
    
     private void rechercher() 
         {      
        FilteredList<Remorquage>filteredData = new FilteredList<>(remList, b->true);
        rechercher.textProperty().addListener((observable, oldValue, newValue)->{
            filteredData.setPredicate(Remorquage -> {
    if (newValue == null || newValue.isEmpty()) {
        return true;
    }
    String lowerCaseFilter = newValue.toLowerCase();
    if (Remorquage.getName().toLowerCase().contains(lowerCaseFilter)) {
        return true;
    } else if (Remorquage.getPrenom().toString().contains(lowerCaseFilter)) {
        return true;
    } else {
        return false;
    }
});        
        });
        SortedList<Remorquage>sortedData = new SortedList<>(filteredData);
        sortedData.comparatorProperty().bind(tableRemorquage.comparatorProperty());
        
        tableRemorquage.setItems(sortedData);
    }

    @FXML
    private void pdfsignal(MouseEvent event) throws SQLException, DocumentException, FileNotFoundException, IOException {
        
        String sql = "SELECT * FROM remorquage";
       
    ste=mc.prepareStatement(sql);
    ResultSet rs=ste.executeQuery();

    Document doc = new Document();
    PdfWriter.getInstance(doc, new FileOutputStream("./ListeDesRemorquages.pdf"));

    doc.open();
   
    doc.add(new Paragraph("   "));
    doc.add(new Paragraph(" *********************************** Liste Des Remorquages *********************************** "));
    doc.add(new Paragraph("   "));

    PdfPTable table = new PdfPTable(3);
    table.setWidthPercentage(125);
    PdfPCell cell;

    cell = new PdfPCell(new Phrase("name", FontFactory.getFont("Comic Sans MS", 14)));
    cell.setHorizontalAlignment(Element.ALIGN_CENTER);
    
    table.addCell(cell);
   
    cell = new PdfPCell(new Phrase("prenom", FontFactory.getFont("Comic Sans MS", 14)));
    cell.setHorizontalAlignment(Element.ALIGN_CENTER);
    
    table.addCell(cell);
    
    cell = new PdfPCell(new Phrase("email", FontFactory.getFont("Comic Sans MS", 14)));
    cell.setHorizontalAlignment(Element.ALIGN_CENTER);
    
    table.addCell(cell);
    
     //cell = new PdfPCell(new Phrase("numtel", FontFactory.getFont("Comic Sans MS", 14)));
    //cell.setHorizontalAlignment(Element.ALIGN_CENTER);
    
    //table.addCell(cell);
    
    
  
    while (rs.next()) {

        Remorquage r = new Remorquage();
      
                r.setName(rs.getString("name"));
                r.setPrenom(rs.getString("prenom"));
                r.setEmail(rs.getString("email"));
                
     
      
        cell = new PdfPCell(new Phrase(r.getName(), FontFactory.getFont("Comic Sans MS", 12)));
        cell.setHorizontalAlignment(Element.ALIGN_CENTER);
        table.addCell(cell);
        
        cell = new PdfPCell(new Phrase(((r.getPrenom())), FontFactory.getFont("Comic Sans MS", 12)));
        cell.setHorizontalAlignment(Element.ALIGN_CENTER);
        table.addCell(cell);
        
        cell = new PdfPCell(new Phrase(r.getEmail(), FontFactory.getFont("Comic Sans MS", 12)));
        cell.setHorizontalAlignment(Element.ALIGN_CENTER);
        table.addCell(cell);
        
        
        // cell = new PdfPCell(new Phrase(r.getNumtel(), FontFactory.getFont("Comic Sans MS", 12)));
        //cell.setHorizontalAlignment(Element.ALIGN_CENTER);
        
      
        
        table.addCell(cell);
    }

    doc.add(table);
    doc.close();
    Desktop.getDesktop().open(new File("./ListeDesRemorquages.pdf"));
        
        
        
    }

    
        
    }
    

