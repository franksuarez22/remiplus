<?php

use yii\widgets\DetailView;
use app\models\Personas;
/* @var $this yii\web\View */
/* @var $model app\models\Personas */
$PersonasLabel = new Personas;
?>
<div class="personas-view">
 
    <h3>Datos básicos</h3><br>
    <table border="1" class="table table-striped table-bordered detail-view">
        <tr>
            <td><b><?=$PersonasLabel->getAttributeLabel("id_nacionalidad")?></b></td>
            <td><?=$Personas->nacionalidad->descripcion_nacionalidad?></td>
            <td><b><?=$PersonasLabel->getAttributeLabel("cedula")?></b></td>
            <td><?=$Personas->cedula?></td>
        </tr>
        <tr>
            <td><b><?=$PersonasLabel->getAttributeLabel("primer_nombre")?></b></td>
            <td><?=$Personas->primer_nombre?></td>
            <td><b><?=$PersonasLabel->getAttributeLabel("primer_apellido")?></b></td>
            <td><?=$Personas->primer_apellido?></td>
        </tr>
        <tr>
            <td><b><?=$PersonasLabel->getAttributeLabel("id_estado")?></b></td>
            <td><?=$Personas->estado->estado?></td>
            <td><b><?=$PersonasLabel->getAttributeLabel("id_municipio")?></b></td>
            <td><?=$Personas->municipio->municipio?></td>
        </tr>
        <tr>
            <td><b><?=$PersonasLabel->getAttributeLabel("id_parroquia")?></b></td>
            <td><?=$Personas->parroquia->parroquia?></td>
            <td><b><?=$PersonasLabel->getAttributeLabel("id_ciudad")?></b></td>
            <td><?=$Personas->ciudad->ciudad?></td>
        </tr>
        <tr>
            <td><b><?=$PersonasLabel->getAttributeLabel("telefono_contacto")?></b></td>
            <td><?=$Personas->telefono_contacto?></td>
            <td><b><?=$PersonasLabel->getAttributeLabel("correo_electronico")?></b></td>
            <td><?=$Personas->correo_electronico?></td>
        </tr>
    </table>

</div>
