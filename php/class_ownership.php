<?php
    class propiedad{
        //Propiedades
        private $id_inmueble;
        public $tipo_inmueble;
        public $arriendo_o_venta;
        public $precio;
        public $pais_ubicacion;
        public $departamento_ubicacion;
        public $municipio_ubicacion;
        public $imagenes;
        public $direccion;
        public $descripcion;
        private $cedula_dueño;
        public $descuento;
        private $id_certificado;
        public $area;
        public $habitaciones;
        public $banos;
        public $area_construida;
        
        //##-----CONSTRUCTOR-----##//
        public function __construct($id_inmueble, $tipo_inmueble, $arriendo_o_venta, $precio,
                                    $pais_ubicacion, $departamento_ubicacion, $municipio_ubicacion,
                                    $imagenes, $direccion, $descripcion, $cedula_dueño, $descuento, $id_certificado,
                                    $area, $habitaciones, $banos, $area_construida){

            $this->id_inmueble            = $id_inmueble;
            $this->tipo_inmueble          = $tipo_inmueble;
            $this->arriendo_o_venta       = ($arriendo_o_venta == 1) ? "arriendo" :  "venta";
            $this->precio                 = $precio;
            $this->pais_ubicacion         = $pais_ubicacion;
            $this->departamento_ubicacion = $departamento_ubicacion;
            $this->municipio_ubicacion    = $municipio_ubicacion;
            $this->imagenes               = $imagenes;
            $this->direccion              = $direccion;
            $this->descripcion            = $descripcion;
            $this->cedula_dueño           = $cedula_dueño;
            $this->descuento              = $descuento;
            $this->id_certificado         = $id_certificado;
            $this->area                   = $area;
            $this->habitaciones           = $habitaciones;
            $this->banos                  = $banos;
            $this->area_construida        = $area_construida;
        }


        //METODOS
        //GETTERS
        public function GET_id_inmueble(){
            return $this->id_inmueble;
        }
        
        public function GET_cedula_dueño(){
            return $this->cedula_dueño;
        }
        public function GET_id_certificado(){
            return $this->id_certificado;
        }
        //SETTERS

        //SELF METHODS
        public function display_images(){
            if (count($this->imagenes) > 0) {
                echo '<div class="swiper mySwiper">
                      <div class="swiper-wrapper">';
            foreach ($this->imagenes as $row) {
                $image_blob = $row['imagen'];

                // Obtener información sobre la imagen
                $image_info = getimagesizefromstring($image_blob);
                $mime_type = $image_info['mime'];

                // Obtener la extensión basada en el tipo MIME
                $extension = image_type_to_extension($image_info[2]);

                // Establecer el encabezado Content-type según la extensión
                if ($extension === '.jpg' || $extension === '.jpeg') {
                echo "<div class='swiper-slide'><img src='data:image/jpg; base64,".base64_encode($image_blob)."'></div>";
                } elseif ($extension === '.png') {
                echo "<div class='swiper-slide'><img src='data:image/jpg; base64,".base64_encode($image_blob)."'></div>";
                } elseif ($extension === '.gif') {
                    echo "<div class='swiper-slide'><img src='data:image/jpg; base64,".base64_encode($image_blob)."'></div>";
                } elseif ($extension === '.jfif') {
                echo "<div class='swiper-slide'><img src='data:image/jpg; base64,".base64_encode($image_blob)."'></div>";
                } else {
                        echo "La imagen no pudo ser cargada";
                }
            }

            echo '<div class="swiper-button-prev"></div>
            <div class="swiper-button-next"></div>
            </div></div>';
            } else {
                echo "El inmueble no posee imágenes";
            }
        }
    }


?>