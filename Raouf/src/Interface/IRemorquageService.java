/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package Interface;

import Entities.Remorquage;
import java.util.List;

/**
 *
 * @author TECHN
 */
public interface IRemorquageService<Remorquage> 
{

    public void ajouterRemorquage(Remorquage r);
    public void supprimerRemorquage(Remorquage r);
    public void modifierRemorquage(Remorquage r);
    public List<Remorquage> afficherRemorquages();
   
    
    public List<Remorquage> getRemorquagesByService(int idService);

    
}
