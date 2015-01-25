<?php

/**
 *	CLasse permettant de generer des graphiques en surface
 * 
 *	@brief		Graphiques avec des zones de surface
 *
 *	@author		Artiom FEDOROV
 *
 */
 
class AreaGraph {

	public $width;
	public $heigh;

	public $image;
	public $marginPercent;

	public $gridColor;
	public $bgColor;

	public $valMaxX;
	public $valMaxY;

	public $resolutionX;
	public $resolutionY;
	
	public $showGraduation;

	//colors
	public $imageColorsArray;


	/**
	 *	Methode constructeur et initialisation
	 *	@brief	Constructeur
	 *
	 */
	 
	function __construct() {
	
		$this->width = 600;
		$this->height = 300;
		$this->marginPercent = 10;
		$this->valMaxX = 5;
		$this->valMaxY = 7;
		$this->resolutionX = 1;
		$this->resolutionY = 1;
		$this->showGraduation = true;


		$this->image = @ImageCreate ($this->width, $this->height);
		$this->bgColor = ImageColorAllocate ($this->image, 255, 255, 255);
		$this->gridColor = ImageColorAllocate ($this->image, 0, 0, 0);
	
	
		// to proto
		$this->marginSizeX = ($this->marginPercent / 100) * $this->width;
		$this->marginSizeY = ($this->marginPercent / 100) * $this->height;
	
		// to method
		$this->imageColorsArray = array(
			0 => ImageColorAllocate ($this->image, 255, 0, 0) ,
			1 => ImageColorAllocate ($this->image, 255, 217, 13) , // yellow
			2 => ImageColorAllocate ($this->image, 0, 255, 0) 
		);
	
	}


	/**
	 *	Methode d'initilisation
	 *	
	 *	@return void
	 */

	public function init() {
		$this->drawGrid();		
		if ($this->showGraduation) {
			$this->drawGridGraudation();
		}
	}
	
	
	/**
	 *	Methode setteur pour le titre
	 *	
	 *	@brief set le titre sur l'image avec une fonte en dur
	 *	@detail	Position le titre dans l'image selon des coordonnées
	 *	@return void
	 *
	 */
	 
	public function setTitle($str) {
		$font_file = PATH_FONTS . "OptimusPrincepsSemiBold.ttf";
			
		imagefttext($this->image, $this->gridFontSize, 0,
					$this->marginSizeX + $this->marginSizeX/2, 
					 $this->marginSizeY,
					  $this->gridColor, $font_file, $str);
	}
	
	
	/**
	 *	Methode getteur pour l'image
	 *
	 *	@brief Getteur pour l'image
	 *	@return Renvoie l'image 
	 *
	 */
	 
	public function getImage() {
		return $this->image;
	}




	/**
	 *	Methode qui dessine toute la data
	 *	
	 *	@param	data	Prend en parametre l'ensemble des datas
	 *	@detail Encodage couleurs est le suivant
	 *			// 0 = red
	 *			// 1 = yellow
	 *			// 2 = green
	 *			// $data[x][classe] = nbr
	 *	@return void
	 *
	 */


	public function drawData($data) {

		$count = count($data);
		$i = 0;
		while ($i < ($count-1)) {
		
			$item1 = $data[$i];
			$item2 = $data[$i+1];
			$LastValue1 = 0;
			$LastValue2 = 0;
			
			foreach($this->imageColorsArray as $key => $color) {
								
				$values = array(
					$this->marginSizeX + $this->stepX * ($i + 1), $this->height - $this->marginSizeY -  $this->stepY * $LastValue1, // en bas a gauche;
					$this->marginSizeX + $this->stepX * ($i + 1), $this->height - $this->marginSizeY - $this->stepY * ($item1[$key] + $LastValue1) ,// en haut a gauche;
					$this->marginSizeX + $this->stepX * ($i+2), $this->height - $this->marginSizeY - $this->stepY * ($item2[$key] + $LastValue2) , // en haut a droite;								
					$this->marginSizeX + $this->stepX * ($i+2), $this->height - $this->marginSizeY - $this->stepY * $LastValue2 , // en bas a droite;
				);
				$LastValue1 += $item1[$key];
				$LastValue2 += $item2[$key];
				imagefilledpolygon($this->image, $values, count( $values ) / 2, $this->imageColorsArray[$key]);
			}
			$i++;
		}
	}
	
	
	/**
	 *	Empty
	 *	@todo: investiguer
	 *
	 */
	 
	public function adaptValMaxFromData() {
	
	}


	/**
	 *	Methode qui dessine la grille dans l'image
	 *
	 *	@details cette methode couvre les absices et les ordonnées	
	 *	@return void
	 *
	 */
	 
	public function drawGrid() {
			
		$marginSizeX = $this->marginSizeX;
		$marginSizeY = $this->marginSizeY;
		
		imageline ( $this->image , $marginSizeX , $this->height - $marginSizeY, $marginSizeX , $marginSizeY , $this->gridColor );
		imageline ( $this->image , $marginSizeX , $this->height - $marginSizeY, $this->width - $marginSizeX , $this->height - $marginSizeY , $this->gridColor );
	
	}


	/**
	 *	Methode qui dessine les graduations de la grille dans l'image
	 *
	 *	@details cette methode couvre les absices et les ordonnées
	 *	@return void
	 *
	 */
	 
	public function drawGridGraudation() {
			
		$nbrX = (int) (( $this->valMaxX / $this->resolutionX ) + 1 );
		$nbrY = (int) (( $this->valMaxY / $this->resolutionY ) + 1 );
			
		$marginSizeX = $this->marginSizeX;
		$marginSizeY = $this->marginSizeY;
		
		$sizeX = 5;
		$sizeY = 5;

		$lineWidthPX = $this->width - ($marginSizeX * 2);		
		$lineHeightPX = $this->height - ($marginSizeY * 2);
		
		$this->stepX = $stepX = (int) ($lineWidthPX / $nbrX);
		$this->stepY = $stepY = (int) ($lineHeightPX / $nbrY);
			
		$this->gridFontSize = 13;	
		
		$font_file = PATH_FONTS . "OptimusPrincepsSemiBold.ttf";
			
		for ($i = 0; $i < $nbrX	; $i++) {
			imageline ( $this->image , 
				$marginSizeX + ($i * $stepX),
				$this->height - $marginSizeY - $sizeX,
				$marginSizeX + ($i * $stepX), 
				$this->height - $marginSizeY + $sizeX,
				$this->gridColor );
				
			imagefttext($this->image, $this->gridFontSize, 0,
				$marginSizeX + ($i * $stepX) - ($this->gridFontSize / 3), 
				$this->height - $marginSizeY + ($sizeX * 2) + $this->gridFontSize, $this->gridColor, $font_file, $i);
				
		}

		for ($i = 1; $i < $nbrY	; $i++) {
				imageline (
					 $this->image , 
						 $marginSizeX - $sizeY, 
						 $this->height - $marginSizeY - ($i * $stepY),
						 $marginSizeX + $sizeY, 
						 $this->height - $marginSizeY - ($i * $stepY),
					 	$this->gridColor );
					 	
				imagefttext($this->image, $this->gridFontSize, 0,
					$marginSizeX - $sizeY - ($this->gridFontSize), 
					 $this->height - $marginSizeY - ($i * $stepY) +   ($this->gridFontSize / 2),
					  $this->gridColor, $font_file, $i);
		}	
	
	}


}
