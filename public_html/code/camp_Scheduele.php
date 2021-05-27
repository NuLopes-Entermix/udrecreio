<?PHP
/*
Simfatic Forms Main Form processor script

This script does all the server side processing. 
(Displaying the form, processing form submissions,
displaying errors, making CAPTCHA image, and so on.) 

All pages (including the form page) are displayed using 
templates in the 'templ' sub folder. 

The overall structure is that of a list of modules. Depending on the 
arguments (POST/GET) passed to the script, the modules process in sequence. 

Please note that just appending  a header and footer to this script won't work.
To embed the form, use the 'Copy & Paste' code in the 'Take the Code' page. 
To extend the functionality, see 'Extension Modules' in the help.

*/

@ini_set("display_errors", 1);//the error handler is added later in FormProc
error_reporting(E_ALL);

require_once(dirname(__FILE__)."/includes/camp_Scheduele-lib.php");
$formproc_obj =  new SFM_FormProcessor('camp_Scheduele');
$formproc_obj->initTimeZone('Europe/Lisbon');
$formproc_obj->setFormID('0eca5080-ce96-4f34-81d3-d1144331bd30');
$formproc_obj->setRandKey('aea1f26e-afbf-4075-a8d8-0f045dc5483e');
$formproc_obj->setFormKey('779d3e73-c779-4214-8c58-7cba9c0730f1');
$formproc_obj->setLocale('pt','dd/MM/yyyy');
$formproc_obj->setEmailFormatHTML(true);
$formproc_obj->EnableLogging(false);
$formproc_obj->SetErrorEmail('nunoslopesster@gmail.com');
$formproc_obj->SetDebugMode(false);
$formproc_obj->setIsInstalled(true);
$formproc_obj->SetPrintPreviewPage(sfm_readfile(dirname(__FILE__)."/templ/camp_Scheduele_print_preview_file.txt"));
$formproc_obj->SetSingleBoxErrorDisplay(true);
$formproc_obj->setFormPage(0,sfm_readfile(dirname(__FILE__)."/templ/camp_Scheduele_form_page_0.txt"));
$formproc_obj->AddElementInfo('Name','text','');
$formproc_obj->AddElementInfo('Email','email','');
$formproc_obj->AddElementInfo('Phone','telephone','');
$formproc_obj->AddElementInfo('DatePicker','datepicker','');
$formproc_obj->AddElementInfo('Horario','text','');
$formproc_obj->AddDefaultValue('Name','Insira o Nome');
$formproc_obj->SetHiddenInputTrapVarName('tea0b23c7510b4c8c8694');
$formproc_obj->SetFromAddress('geral@udrecreio.pt');
$formproc_obj->InitSMTP('smtp-pt.securemail.pro','geral@udrecreio.pt','9D0ABB6B11C31A12307469B61FAEF4AD',587);
$page_renderer =  new FM_FormPageDisplayModule();
$formproc_obj->addModule($page_renderer);

$validator =  new FM_FormValidator();
$validator->addValidation("Name","required","Por favor insira o seu nome");
$validator->addValidation("Email","email","Por favor insira o seu email");
$validator->addValidation("Phone","required","Por favor insira o seu contacto telefónico");
$validator->addValidation("DatePicker","required","Por favor insira a data pretendida");
$validator->addValidation("Horario","required","Por favor reveja a informção\nDeverá estar no formato HH:MM");
$validator->addValidation("Horario","regexp=/^([0-1]?\\d|2[0-4])[:\\.][0-5]\\d$/","Please enter a valid input for Horario");
$formproc_obj->addModule($validator);

$confirmpage =  new FM_ConfirmPage(sfm_readfile(dirname(__FILE__)."/templ/camp_Scheduele_confirm_page.txt"));
$confirmpage->SetButtonCode(sfm_readfile(dirname(__FILE__)."/templ/camp_Scheduele_confirm_button_code.txt"),sfm_readfile(dirname(__FILE__)."/templ/camp_Scheduele_edit_button_code.txt"),sfm_readfile(dirname(__FILE__)."/templ/camp_Scheduele_print_button_code.txt"));
$formproc_obj->addModule($confirmpage);

$data_email_sender =  new FM_FormDataSender(sfm_readfile(dirname(__FILE__)."/templ/camp_Scheduele_email_subj.txt"),sfm_readfile(dirname(__FILE__)."/templ/camp_Scheduele_email_body.txt"),'%Email%');
$data_email_sender->AddToAddr('Nuno Lopes <udrecreiovnr@gmail.com>');
$data_email_sender->AddToAddr('Modalidades <modalidades.udrecreiovnr@gmail.com>');
$formproc_obj->addModule($data_email_sender);

$tupage =  new FM_ThankYouPage(sfm_readfile(dirname(__FILE__)."/templ/camp_Scheduele_thank_u.txt"));
$formproc_obj->addModule($tupage);

$page_renderer->SetFormValidator($validator);
$formproc_obj->ProcessForm();

?>