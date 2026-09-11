<?php

class Responsabile {

    public $id_utente;
    public $cv_pdf;
    public $username;

    public function __construct(
        $id_utente,
        $cv_pdf = null,
        $username = null
    ) {

        $this->id_utente = $id_utente;
        $this->cv_pdf = $cv_pdf;
        $this->username = $username;
    }

    /**
     * Verifica se il profilo ha un CV caricato
     * @return bool
     */
    public function hasCv() {

        return !empty($this->cv_pdf);
    }

    /**
     * Valida l'estensione del file CV
     * @param string $filename
     * @return bool
     */
    public static function isValidCvExtension($filename) {

        $estensione = strtolower(
            pathinfo($filename, PATHINFO_EXTENSION)
        );

        return $estensione === 'pdf';
    }

    /**
     * Valida il MIME type del CV
     * @param string $filePath
     * @return bool
     */
    public static function isValidCvMimeType($filePath) {

        $finfo = finfo_open(FILEINFO_MIME_TYPE);

        $mime = finfo_file(
            $finfo,
            $filePath
        );

        finfo_close($finfo);

        return $mime === 'application/pdf';
    }
}

?>