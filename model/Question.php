<?php
class question {
    private $id_question;
    private $titre;
    private $date_creation;
    private $contenu;
    
    
    public function __construct($id_question, $titre ,$date_creation=null, $contenu=null ) {
       
        $this->id_question = $id_question ;
        $this->titre = $titre;
        $this->date_creation = $date_creation;
        $this->contenu = $contenu ;
        

    }
    /**
     * Get the value of id_utilisateur
     */ 
    public function getId_question()
    {
        return $this->id_question;
    }

    /**
     * Set the value of id_utilisateur
     *
     * @return  self
     */ 
    public function setId_question($id_question)
    {
        $this->id_question = $id_question;

        return $this;
    }

    /**
     * Get the value of titre
     */ 
    public function getTitre()
    {
        return $this->titre;
    }

    /**
     * Set the value of titre
     *
     * @return  self
     */ 
    public function setTitre($titre)
    {
        $this->titre = $titre;

        return $this;
    }

    /**
     * Get the value of date_creaction
     */ 
    public function getDate_creation()
    {
        return $this->date_creation;
    }

    /**
     * Set the value of date_creaction
     *
     * @return  self
     */ 
    public function setDate_creation($date_creation)
    {
        $this->date_creation = $date_creation;

        return $this;
    }

    /**
     * Get the value of contenu
     */ 
    public function getContenu()
    {
        return $this->contenu;
    }

    /**
     * Set the value of contenu
     *
     * @return  self
     */ 
    public function setContenu($contenu)
    {
        $this->contenu = $contenu;

        return $this;
    }
    }



    