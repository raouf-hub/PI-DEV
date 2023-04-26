/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package Services;

import Entities.Remorquage;
import Entities.Service;
import Interface.IRemorquageService;
import MyConnection.MyConnection;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.sql.Statement;
import java.util.ArrayList;
import java.util.List;

/**
 *
 * @author TECHN
 */
public class RemorquageService implements IRemorquageService<Remorquage>{
    
    
    
    
    
    
     @Override
     public void ajouterRemorquage(Remorquage r) {
        
        try {
             String requete= "INSERT INTO remorquage (ids,name,prenom,email,numtel)"
                    + "VALUES (?,?,?,?,?)";
            PreparedStatement pst = MyConnection.getInstance().getCnx()
                    .prepareStatement(requete);
            
            
            pst.setInt(1, r.getIds());
            pst.setString(2,r.getName());
            pst.setString(3,r.getPrenom());
            pst.setString(4,r.getEmail());
            pst.setInt(5,r.getNumtel());  
            
            
            pst.executeUpdate();
            System.out.println("Remorquage ajoutée");
            
        } catch (SQLException ex) {
            System.out.println(ex.getMessage());
        }
    
    }
    
    
   
     @Override
    public void supprimerRemorquage(Remorquage r) {
        try {
            String requete = "DELETE FROM remorquage where idremorquage=?";
            PreparedStatement pst = MyConnection.getInstance().getCnx()
                    .prepareStatement(requete);
            pst.setInt(1, r.getIdremorquage());
            pst.executeUpdate();
            System.out.println("Remorquage supprimée");
        } catch (SQLException ex) {
            System.out.println(ex.getMessage());
        }
    }
    
    
    
    
    
     @Override
    public void modifierRemorquage(Remorquage r) {
        try {
            String requete = "UPDATE remorquage SET ids=?,name=?,prenom=?,email=?,numtel=? WHERE idremorquage=?";
            PreparedStatement pst = MyConnection.getInstance().getCnx()
                    .prepareStatement(requete);
           
            pst.setInt(1,r.getIds());
            pst.setString(2, r.getName());
            pst.setString(3, r.getPrenom());
             pst.setString(4, r.getEmail());
             pst.setInt(5, r.getNumtel());
             pst.setInt(6,r.getIdremorquage());
            
            pst.executeUpdate();
            System.out.println("Remorquage modifiée");
        } catch (SQLException ex) {
            System.out.println(ex.getMessage());
        }
    } 
 
    
     @Override
     public List<Remorquage> afficherRemorquages() {        
         List<Remorquage> RemorquagesList = new ArrayList<>();
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
                
                
                
                System.out.println("the added Remorquages :" +r.toString());
                RemorquagesList.add(r);
            }
        } catch (SQLException ex) {
            System.out.println(ex.getMessage());
        }
        return RemorquagesList;
     
     
     
     }
    
   
    
   @Override
public List<Remorquage> getRemorquagesByService(int idService) {
    List<Remorquage> remorquages = new ArrayList<>();
    try {
        
        String requete ="SELECT * FROM remorquage WHERE ids = ?";
        
       PreparedStatement ps = MyConnection.getInstance().getCnx()
                    .prepareStatement(requete);
       
        ps.setInt(1, idService);
        ResultSet rs = ps.executeQuery();
        while (rs.next()) {
           Remorquage r = new Remorquage();
                r.setIdremorquage(rs.getInt("idremorquage"));
                r.setIds(rs.getInt("ids"));
                r.setName(rs.getString("name"));
                r.setPrenom(rs.getString("prenom"));
                r.setEmail(rs.getString("email"));
                r.setNumtel(rs.getInt("numtel"));
                
                remorquages.add(r);
        }
    } catch (SQLException ex) {
        System.out.println(ex.getMessage());
    }
    return remorquages;
}

    
    
    
}
