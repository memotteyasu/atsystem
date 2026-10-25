<?php
if (!empty( $valerror['data'])){
    for($i=0; $i<count( $valerror['data']); $i++){
        echo $valerror['data'][$i];
    }
} else {
    echo h( $this->data['Reservetran']['data']);
}
?>