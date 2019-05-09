<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Form_validation extends CI_Form_validation
{
    function __construct()
    {
        parent::__construct();
    }

    function alpha_dash_space($valeur)
    {
        $pattern = "/^([A-Za-z0-9àáâãäåçèéêëìíîïðòóôõöùúûüýÿ])([0-9A-Za-zàáâãäåçèéêëìíîïðòóôõöùúûüýÿ\s_\(\)\[\]'\":.,-])*([A-Za-z0-9àáâãäåçèéêëìíîïðòóôõöùúûüýÿ\(\)\[\]'\":.,])$/";

        if (preg_match($pattern, $valeur) === 1)
        {
            return TRUE;
        }
        else
        {
            $this->set_message('alpha_dash_space', "Le champ %s ne peut contenir que des caract&egrave;res alphanum&eacute;riques, des espaces, des tirets bas (underscore) et des traits d'union.");
            return FALSE;
        }
    }
}