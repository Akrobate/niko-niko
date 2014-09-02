<?php
// on spécifie le type de document que l'on va créer (ici une image au format PNG
if (!$preventTPL) {
	header ("Content-type: image/png");
	ImagePng ($image);

}
