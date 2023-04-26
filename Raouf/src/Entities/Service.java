/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package Entities;

/**
 *
 * @author TECHN
 */
public class Service {
    
    private int id;
    private String libelleService;
    private String nomService;

    public Service() {
    }

    public Service(int id, String libelleService, String nomService) {
        this.id = id;
        this.libelleService = libelleService;
        this.nomService = nomService;
    }

    public Service(String libelleService, String nomService) {
        this.libelleService = libelleService;
        this.nomService = nomService;
    }

    public int getId() {
        return id;
    }

    public void setId(int id) {
        this.id = id;
    }

    public String getLibelleService() {
        return libelleService;
    }

    public void setLibelleService(String libelleService) {
        this.libelleService = libelleService;
    }

    public String getNomService() {
        return nomService;
    }

    public void setNomService(String nomService) {
        this.nomService = nomService;
    }

    
    @Override
    public String toString() {
        return "Service{" + "id=" + id + ", libelleService=" + libelleService + ", nomService=" + nomService + '}';
    }
    
    
    
    
    
}
