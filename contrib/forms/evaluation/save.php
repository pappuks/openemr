<?php
require_once("../../globals.php");
require_once("$srcdir/api.inc");
require("C_FormEvaluation.class.php");

if (!verifyCsrfToken($_POST["csrf_token_form"])) {
    csrfNotVerified();
}

$c = new C_FormEvaluation();
echo $c->default_action_process($_POST);
@formJump();
