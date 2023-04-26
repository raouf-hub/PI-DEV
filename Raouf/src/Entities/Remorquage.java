/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package Entities;

import java.util.List;

/**
 *
 * @author TECHN
 */
public class Remorquage {
    private int idremorquage;
    private int ids;
    private String name;
    private String prenom;
    private String email;
    private int numtel;

    public Remorquage() {
    }

    public Remorquage(int ids, String name, String prenom, String email, int numtel) {
        this.ids = ids;
        this.name = name;
        this.prenom = prenom;
        this.email = email;
        this.numtel = numtel;
    }

    public Remorquage(int idremorquage, int ids, String name, String prenom, String email, int numtel) {
        this.idremorquage = idremorquage;
        this.ids = ids;
        this.name = name;
        this.prenom = prenom;
        this.email = email;
        this.numtel = numtel;
    }

    public int getIdremorquage() {
        return idremorquage;
    }

    public void setIdremorquage(int idremorquage) {
        this.idremorquage = idremorquage;
    }

    public int getIds() {
        return ids;
    }

    public void setIds(int ids) {
        this.ids = ids;
    }

    public String getName() {
        return name;
    }

    public void setName(String name) {
        this.name = name;
    }

    public String getPrenom() {
        return prenom;
    }

    public void setPrenom(String prenom) {
        this.prenom = prenom;
    }

    public String getEmail() {
        return email;
    }

    public void setEmail(String email) {
        this.email = email;
    }

    public int getNumtel() {
        return numtel;
    }

    public void setNumtel(int numtel) {
        this.numtel = numtel;
    }

    @Override
    public String toString() {
        return "Remorquage{" + "idremorquage=" + idremorquage + ", ids=" + ids + ", name=" + name + ", prenom=" + prenom + ", email=" + email + ", numtel=" + numtel + '}';
    }
    




    
    
    
    
    
}
