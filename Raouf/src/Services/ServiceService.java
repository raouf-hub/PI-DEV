/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package Services;

import Entities.Service;
import Interface.IServiceService;
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
public class ServiceService implements IServiceService<Service>{
    
    
    
     @Override
     public void ajouterService(Service s) {
        
        try {
             String requete= "INSERT INTO service (libelleService,nomService)"
                    + "VALUES (?,?)";
            PreparedStatement pst = MyConnection.getInstance().getCnx()
                    .prepareStatement(requete);
            
            
            pst.setString(1, s.getLibelleService());
            pst.setString(2,s.getNomService());
            
            pst.executeUpdate();
            System.out.println("Service ajoutée");
            
        } catch (SQLException ex) {
            System.out.println(ex.getMessage());
        }
    
    }
    
    
   
     @Override
    public void supprimerService(Service s) {
        try {
            String requete = "DELETE FROM service where id=?";
            PreparedStatement pst = MyConnection.getInstance().getCnx()
                    .prepareStatement(requete);
            pst.setInt(1, s.getId());
            pst.executeUpdate();
            System.out.println("Service supprimée");
        } catch (SQLException ex) {
            System.out.println(ex.getMessage());
        }
    }
    
    
    
    
    
     @Override
    public void modifierService(Service s) {
        try {
            String requete = "UPDATE service SET libelleService=?,nomService=? WHERE id=?";
            PreparedStatement pst = MyConnection.getInstance().getCnx()
                    .prepareStatement(requete);
            pst.setString(1, s.getLibelleService());
            pst.setString(2, s.getNomService());
            
            
            pst.setInt(3,s.getId());
            
            pst.executeUpdate();
            System.out.println("Service modifiée");
        } catch (SQLException ex) {
            System.out.println(ex.getMessage());
        }
    } 
 
    
     @Override
     public List<Service> afficherServices() {        
         List<Service> ServicesList = new ArrayList<>();
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
                
                System.out.println("the added Services :" +s.toString());
                ServicesList.add(s);
            }
        } catch (SQLException ex) {
            System.out.println(ex.getMessage());
        }
        return ServicesList;
     
     
     
     }
    
    
    
    
    
}
