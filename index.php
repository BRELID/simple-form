<?php

/**
 *      DON'T FORGET CHANGE FOR YOUR EMAIL BELOW IN THE TEST ERROR SEND
 *       N'OUBLIEZ PAS DE METTRE VOTRE EMAIL EN DESSOUS DANS LE TEST D'ENVOI
 *      php var $to
 *
 *        AND CHANGE FOR YOUR SPECIFIC SUBJECT BELOW
 *        ET METTRE UN SUJET D'EMAIL EN DESSOUS
 *        php var $subject
 *
 */

/**
 * BEGIN / DEBUT
 * Functions to filter user inputs
 * Fonction pour filtrer les données reçues
 */
function filterName($field)
{
    // Sanitize name
    // Assaini le nom d'utilisateur
    $field = filter_var(trim($field), FILTER_SANITIZE_STRING);

    // Validate name
    // Valide le nom
    if (filter_var($field, FILTER_VALIDATE_REGEXP, array("options" => array("regexp" => "/^[a-zA-Z\s]+$/")))) {
        return $field;
    } else {
        return false;
    }
}
function filterEmail($field)
{
    // Sanitize email address
    // Assaini l'email
    $field = filter_var(trim($field), FILTER_SANITIZE_EMAIL);

    // Validate email address
    // Valide l'email
    if (filter_var($field, FILTER_VALIDATE_EMAIL)) {
        return $field;
    } else {
        return false;
    }
}
function filterString($field)
{
    // Sanitize string
    // Assaini une chaine de caractère
    $field = filter_var(trim($field), FILTER_SANITIZE_STRING);
    if (!empty($field)) {
        return $field;
    } else {
        return false;
    }
}
/**
 * END / FIN
 * of functions / des fonctions
 */

/**
 * TRANSLATE VAR
 * TRADUCTION VARIABLE
 *
 */

//Select your language
//Selection de la langue
// fr / en
if (isset($_GET['lang']) && $_GET['lang'] == "en") {
    $lang = "en";
} else {
    $lang = "fr";
}

// first, we don't show modals
// Nous n'affichons de base pas les modals
$modal = false;
$modal_error = false;

//Content and errors are changed according to language
//On change le contenu et les erreurs selon la langue
$content["fr"]["name"] = "Nom";
$content["fr"]["name_placeholder"] = "Votre nom et prénom";
$content["fr"]["name_error"] = "Le nom est requis";
$content["fr"]["email_placeholder"] = "Votre adresse mail";
$content["fr"]["email_error"] = "Une adresse mail valide est requise";
$content["fr"]["tel_placeholder"] = "Votre numéro de téléphone";
$content["fr"]["message_placeholder"] = "Votre message";
$content["fr"]["message_error"] = "Une message est requis";
$content["fr"]["requis"] = "requis";
$content["fr"]["send_button"] = "Envoyer";

$content["en"]["name"] = "Name";
$content["en"]["name_placeholder"] = "your lastname and firstname";
$content["en"]["name_error"] = "Name is required";
$content["en"]["email_placeholder"] = "Your email address";
$content["en"]["email_error"] = "A valid email address is required";
$content["en"]["tel_placeholder"] = "Your phone number";
$content["en"]["message_error"] = "A message is required";
$content["en"]["message_placeholder"] = "Your message";
$content["en"]["requis"] = "required";
$content["en"]["send_button"] = "Send";

$content["fr"]["modal_button"] = "Fermer";
$content["fr"]["modal_message"] = "Votre message a bien été envoyé.";
$content["fr"]["modal_title"] = "Message envoyé";
$content["fr"]["modal_error_title"] = "Message non envoyé";

$content["fr"]["error_name_empty_submit"] = "Veuillez entrer votre nom.";
$content["fr"]["error_name_nonvalid_submit"] = "Veuillez entrer un nom valide.";
$content["fr"]["error_email_empty_submit"] = "Veuillez entrer votre adresse e-mail.";
$content["fr"]["error_email_nonvalid_submit"] = "Veuillez entrer une adresse e-mail valide.";
$content["fr"]["error_message_empty_submit"] = "Veuillez entrer votre message.";
$content["fr"]["error_message_nonvalid_submit"] = "Veuillez entrer un message valide.";
$content["fr"]["error_send_message"] = "Problème avec le service d'envoi de message";

$content["en"]["modal_button"] = "Close";
$content["en"]["modal_message"] = "Your message has been sent successfully.";
$content["en"]["modal_title"] = "Sent message";
$content["en"]["modal_error_title"] = "Message not sent";

$content["en"]["error_name_empty_submit"] = "Please enter your name.";
$content["en"]["error_name_nonvalid_submit"] = "Please enter a valid name.";
$content["en"]["error_email_empty_submit"] = "Please enter your email address.";
$content["en"]["error_email_nonvalid_submit"] = "Please enter a valid email address.";
$content["en"]["error_message_empty_submit"] = "Please enter your comment.";
$content["en"]["error_message_nonvalid_submit"] = "Please enter a valid comment.";
$content["en"]["error_send_message"] = "Problem with the mail service";
//We test if the form is submitted
//On test si le formulaire a été envoyé
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate user name
    if (empty($_POST["contact_name"])) {
        $nameErr = $content[$lang]["error_name_empty_submit"];
    } else {
        $name = filterName($_POST["contact_name"]);
        if ($name == false) {
            $nameErr = $content[$lang]["error_name_nonvalid_submit"];
        }
    }

    // Validate email address
    if (empty($_POST["contact_email"])) {
        $emailErr = $content[$lang]["error_email_empty_submit"];
    } else {
        $email = filterEmail($_POST["contact_email"]);
        if ($email == false) {
            $emailErr = $content[$lang]["error_email_empty_submit"];
        }
    }

    // Validate message
    // Validation du message
    if (empty($_POST["contact_message"])) {
        $messageErr = $content[$lang]["error_message_empty_submit"];
    } else {
        $message = filterString($_POST["contact_message"]);
        if ($message == false) {
            $messageErr = $content[$lang]["error_message_empty_submit"];
        }
    }

    // Check input errors before sending email
    // On vérifie s'il n'y a pas eu d'erreurs
    $modal_error = false;
    if (empty($nameErr) && empty($emailErr) && empty($messageErr)) {

        // DON'T FORGET CHANGE FOR YOUR EMAIL HERE
        // N'OUBLIEZ PAS DE METTRE VOTRE EMAIL ICI
        // Recipient email address
        // Votre adresse mail
        $to = 'brahim.relid@gmail.com';

        //CHANGE FOR YOUR SPECIFIC SUBJECT
        //METTRE UN SUJET D'EMAIL
        //$subject = "Contact provenant de mon formulaire de portfolio";
        $subject = "Contact form from my portfolio";

        // Create email headers
        $headers = 'From: ' . $email . "\r\n" .
        'Reply-To: ' . $email . "\r\n" .
        'X-Mailer: PHP/' . phpversion();

        //if tel is set, we add it in the message
        //si il y a le tel, on l'ajout à message
        if (!empty($_POST["contact_tel"])) {
            $tel = filterString($_POST["contact_tel"]);
            $message .= " <br/>Tel :" . $tel;
        }

        // Sending email
        //envoi de l'email
        if (mail($to, $subject, $message, $headers)) {
            $modal = true;
        } else {
            //if we've got a send problem we see this message
            //si on a un problème d'envoi, on affiche ce message
            $nameErr = $content[$lang]["error_send_message"];
            $modal_error = true;
        }

    } else {
        //Else we show error modal
        //Sinon on montre la modal d'erreur
        $modal_error = true;
    }
}

// Active for try modals
// Activer pour essayer la modals
//$modal=true;
//$modal_error=true;
?>
<!DOCTYPE html>
<html lang="<?php echo $lang; ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4" crossorigin="anonymous">
    <title>SIMPLE FORM</title>
</head>

<body>

    <div class="container">
        <div class="row">
            <div class="col-sm-8 offset-sm-2">
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-text">Simple Form</h3>
                    </div>
                    <div class="card-body">
                        <form id="contact" method="post" class="form-horizontal" method="post" action="./index.php?lang=<?php echo $lang ?>">
                            <!-- Name / Nom -->
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="contact_name"><?php echo $content[$lang]["name"]; ?> <span>(<?php echo $content[$lang]["requis"] ?>)</span>:</label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" id="contact_name" name="contact_name" placeholder="<?php echo $content[$lang]["name_placeholder"]; ?>" />
                                </div>
                            </div>
                            <!-- Email -->
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="contact_email">Email <span>(<?php echo $content[$lang]["requis"] ?>)</span>:</label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" id="contact_email" name="contact_email" placeholder="<?php echo $content[$lang]["email_placeholder"]; ?>" />
                                </div>
                            </div>

                            <!-- Tel -->
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="contact_email">Tel:</label>
                                <div class="col-sm-6">
                                    <input type="tel" class="form-control" id="contact_tel" name="contact_tel" placeholder="<?php echo $content[$lang]["tel_placeholder"]; ?>" />
                                </div>
                            </div>

                            <!-- Message -->
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="contact_email">Message <span>(<?php echo $content[$lang]["requis"] ?>)</span>:</label>
                                <div class="col-sm-6">
                                    <textarea class="form-control" id="contact_message" name="contact_message" required="required" placeholder="<?php echo $content[$lang]["message_placeholder"]; ?>"></textarea>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-9 offset-sm-4">
                                    <button type="submit" class="btn btn-primary" name="submit" value="<?php echo $content[$lang]["send_button"]; ?>"><?php echo $content[$lang]["send_button"]; ?></button>
                                    <button type="reset" class="btn btn-secondary">Reset</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>






    <!-- Modal which appears if the form has been sent -->
    <!-- Modal qui apparaît si le formulaire a bien été envoyé -->
    <div class="modal fade" id="send_form_Modal" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ModalLabel"><?php echo $content[$lang]["modal_title"]; ?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <?php echo $content[$lang]["modal_message"]; ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" data-dismiss="modal"><?php echo $content[$lang]["modal_button"]; ?></button>
                </div>
            </div>
        </div>
    </div>

    <!-- Error modal -->
    <!-- Modal en cas d'erreur -->
    <div class="modal fade" id="error_form_Modal" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ModalLabel"><?php echo $content[$lang]["modal_error_title"]; ?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <?php
if (!empty($nameErr)) {
    echo $nameErr . " <br />";
}

if (!empty($emailErr)) {
    echo $emailErr . " <br />";
}

if (!empty($messageErr)) {
    echo $messageErr . " <br />";
}

?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" data-dismiss="modal"><?php echo $content[$lang]["modal_button"]; ?></button>
                </div>
            </div>
        </div>
    </div>




    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.1/jquery.validate.min.js"></script>
    <script type="text/javascript">
        //For more infos How to use jQuery Validation Plugin
        //Pour plus d'infos sur l'utilisation de jQuery Validation Plugin
        //https://github.com/jquery-validation/jquery-validation
        $.validator.setDefaults({
            submitHandler: function() {

                $('#contact').attr('action', "./index.php?lang=<?php echo $lang ?>").submit();
            }
        });


        $(document).ready(function() {



            <?php
if ($modal) {
    //We open modal if the form has been sent
    //On ouvre la modal si le formulaire a bien été envoyé
    ?> $("#send_form_Modal").modal("show"); <?php
} else if ($modal_error) {
    ?> $("#error_form_Modal").modal("show"); <?php
}

?>


            //name refers to the input/textarea name
            //le nom fait référence au name dans l'input/textarea
            $("#contact").validate({
                rules: {
                    contact_name: "required",
                    contact_email: {
                        required: true,
                        email: true
                    },
                    contact_message: "required"
                },
                messages: {
                    contact_name: "<?php echo $content[$lang]["name_error"]; ?>",
                    contact_email: "<?php echo $content[$lang]["email_error"]; ?>",
                    contact_message: "<?php echo $content[$lang]["message_error"]; ?>"
                },
                errorElement: "em",
                errorPlacement: function(error, element) {
                    // Add the `invalid-feedback` class to the error element
                    //Ajouter la classe `invalid-feedback` à l'élément error
                    error.addClass("invalid-feedback");
                    if (element.prop("type") === "checkbox") {
                        error.insertAfter(element.next("label"));
                    } else {
                        error.insertAfter(element);
                    }
                },
                highlight: function(element, errorClass, validClass) {
                    //if we've an error, we add is-invalid class and remove is-valid
                    //si nous avons une erreur, nous ajoutons la classe is-invalid et supprimons is-valid
                    $(element).addClass("is-invalid").removeClass("is-valid");
                },
                unhighlight: function(element, errorClass, validClass) {
                    //reverse of above
                    //le contraire de au-dessus
                    $(element).addClass("is-valid").removeClass("is-invalid");
                }
            });
        });
    </script>
</body>

</html>