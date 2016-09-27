<?php
// Projet Réservations M2L - version web mobile
// fichier : Classe Salle
// Rôle : la classe Salle représente les sales
// Création : 27/09/16 Par Florian
// Mise à jour : Pas de MAJ

class Salle
{
// ------------------------------------------------------------------------------------------------------
// ---------------------------------- Membres privés de la classe ---------------------------------------
// ------------------------------------------------------------------------------------------------------

private $id;			// identifiant de la salle (numéro automatique dans la BDD)
private $room_name;		// nom de la salle
private $capacity;		// capacité de la salle
private $area_name;		// nom

// ------------------------------------------------------------------------------------------------------
// ----------------------------------------- Constructeur -----------------------------------------------
// ------------------------------------------------------------------------------------------------------

public function Salle($unId, $unroom_name, $unCapacity, $unarea_name) {
	$this->id = $unId;
	$this->room_name = $unroom_name;
	$this->Capacity = $unCapacity;
	$this->area_name = $unarea_name;
}

// ------------------------------------------------------------------------------------------------------
// ---------------------------------------- Getters et Setters ------------------------------------------
// ------------------------------------------------------------------------------------------------------

public function getId()	{return $this->id;}
public function setId($unId) {$this->id = $unId;}

public function getRoom_name()	{return $this->room_name;}
public function setRoom_name($unroom_name) {$this->room_name = $unroom_name;}

public function getCapacity()	{return $this->Capacity;}
public function setCapacity($unCapacity) {$this->Capacity = $unCapacity;}

public function getAreaName()	{return $this->area_name;}
public function setAreaname($unarea_name) {$this->area_name = $unarea_name;}

// ------------------------------------------------------------------------------------------------------
// -------------------------------------- Méthodes d'instances ------------------------------------------
// ------------------------------------------------------------------------------------------------------


public function toString() {
	$msg = 'Salle : <br>';
	$msg .= 'id : ' . $this->getId() . '<br>';
	$msg .= 'room_name : ' . $this->getRoom_name() . '<br>';
	$msg .= 'capacity : ' . $this->getCapacity() . '<br>';
	$msg .= 'area_name : ' . $this->getAreaname() . '<br>';
	$msg .= '<br>';


}

} // fin de la classe Salle

// ATTENTION : on ne met pas de balise de fin de script pour ne pas prendre le risque
// d'enregistrer d'espaces après la balise de fin de script !!!!!!!!!!!!






// ATTENTION : on ne met pas de balise de fin de script pour ne pas prendre le risque
// d'enregistrer d'espaces après la balise de fin de script !!!!!!!!!!!!