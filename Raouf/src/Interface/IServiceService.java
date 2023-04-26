/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package Interface;

import java.util.List;

/**
 *
 * @author TECHN
 */
public interface IServiceService<Service> { 

    
    public void ajouterService(Service s);
    public void supprimerService(Service s);
    public void modifierService(Service s);
    public List<Service> afficherServices();

    
}
