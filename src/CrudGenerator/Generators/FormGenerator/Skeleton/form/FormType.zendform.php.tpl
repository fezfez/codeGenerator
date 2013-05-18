<?php echo '<?php'; ?>

namespace <?php echo $this->namespace; ?>\Form;

use <?php echo $this->namespace; ?>\DataObject\<?php echo $this->entity_class; ?>DataObject;

class <?php echo $this->entity_class; ?>Form extends \Zend_Form
{
    protected $_defaultDecorators = array(
        'ViewHelper',
        'Errors',
        array('Description', array('tag' => 'p', 'class' => 'description')),
        array('HtmlTag', array('tag' => 'div')),
        array('Label'),
        array(array('div' => 'HtmlTag'), array('tag' => 'div'))
    );

    public function init()
    {
        $this->setDisableLoadDefaultDecorators(true)
             ->addDecorator('FormElements')
             ->addDecorator('Form');<?php echo "\n\r"; ?>
<?php foreach($this->fields as $field): ?>
<?php 
if(isset($field['id']) && $field['id'] == true) {
    //$type = 'Hidden';
    continue;
} elseif($field['type'] == 'text') {
    $type = 'Textarea';
} else {
    $type = 'Text';
}
$length = str_repeat(' ', strlen('a' . $field['fieldName']));
?>
            $<?php echo $metadata->getName()['fieldName']; ?> = new \Zend_Form_Element_<?php echo $type; ?>('<?php echo $metadata->getName()['fieldName']; ?>');
            $<?php echo $metadata->getName()['fieldName']; ?>->setLabel('<?php echo $metadata->getName()['fieldName']; ?>')
<?php if ($field['nullable'] === false && isset($field['id']) && $field['id'] === false): ?>
            <?php echo $length; ?>->setRequired(true)
<?php endif; ?>
<?php if ($field['type'] == 'integer'): ?>
            <?php echo $length; ?>->addValidator(new \Zend_Validate_Int())
<?php endif; ?>
<?php if ($field['type'] == 'string'): ?>
            <?php echo $length; ?>->addValidator(new \Zend_Validate_StringLength(array('max' => "<?php echo (!empty($field['length'])) ? $field['length'] : 255; ?>")))
<?php endif; ?>
<?php if ($field['type'] == 'bool'): ?>
            <?php echo $length; ?>->addValidator(new \Zend_Validate_Int())
<?php endif; ?>
<?php if ($field['type'] == 'datetime'): ?>
            <?php echo $length; ?>->addValidator(new \Zend_Validate_Date('mm/dd/yyyy'))
            <?php echo $length; ?>->setAttrib('class', 'datepicker')
            /*->addValidator(new \Zend_Filter_Callback(function($value) {
                return new \DateTime($value);
            }))*/
<?php endif; ?>
<?php if ($field['type'] == 'float'): ?>
            <?php echo $length; ?>->addValidator(new \Zend_Validate_Float())
<?php endif; ?>
<?php if ($field['type'] == 'date'): ?>
            <?php echo $length; ?>->addValidator(new \Zend_Validate_Date())
<?php endif; ?>
            <?php echo $length; ?>->setDecorators($this->_defaultDecorators);
            $this->addElement($<?php echo $metadata->getName()['fieldName']; ?>);<?php echo "\n\r"; ?>
<?php endforeach; ?>

        $submit = new \Zend_Form_Element_Submit("submit");
        $submit->setLabel('Envoyer')
               ->setDecorators($this->_defaultDecorators)
               ->removeDecorator('Label');
        $this->addElement($submit);
    }

    public function getDataObject()
    {
        $dataObject = new <?php echo $this->entity_class; ?>DataObject();
<?php foreach($this->fields as $field => $metadata): ?>
<?php if($metadata == reset($this->fields)): ?>
        $dataObject->set<?php echo $metadata->getName(); ?>($this->getValue('<?php echo $metadata->getName(); ?>'))<?php echo "\n"; ?>
<?php elseif(in_array($metadata->getType(), array("date", "datetime"))): ?>
                   ->set<?php echo $metadata->getName(); ?>(new \DateTime($this->getValue('<?php echo $metadata->getName(); ?>')))<?php if($metadata == end($this->fields)): ?>;<?php endif; ?><?php echo "\n"; ?>
<?php else: ?>
                   ->set<?php echo $metadata->getName(); ?>($this->getValue('<?php echo $metadata->getName(); ?>'))<?php if($metadata == end($this->fields)): ?>;<?php endif; ?><?php echo "\n"; ?>
<?php endif; ?>
<?php endforeach; ?>

       return $dataObject;
    }
}
