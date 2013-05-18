<?php echo '<?php'; ?>

namespace <?php echo $this->namespace; ?>\Form;

use <?php echo $this->namespace; ?>\DataObject\<?php echo $this->entity_class; ?>DataObject;
use <?php echo $this->namespace; ?>\Hydrator\<?php echo $this->entity_class; ?>Hydrator;

class <?php echo $this->entity_class; ?>Form extends \Twitter_Bootstrap_Form_Horizontal
{
    public function init()
    {
<?php foreach($this->fields as $field): ?>
<?php 
if(isset($field['id']) && $field['id'] == true) {
    //$type = 'Hidden';
    continue;
} elseif($field['type'] == 'text') {
    $type = 'textarea';
} else {
    $type = 'text';
}
?>
        $this->addElement('<?php echo $type; ?>', '<?php echo $metadata->getName()['fieldName']; ?>',
            array(<?php if ($field['type'] == 'datetime'): ?>
                'class' => 'datepicker',<?php endif; ?>
                'label' => '<?php echo ucfirst($field['fieldName']); ?>',
                <?php if($type === 'textarea'): ?>
                'cols' => 4,
                'rows' => 4,
                <?php endif; ?>
                <?php if ($field['nullable'] === false): ?>
                'required' => true,
                <?php endif; ?>
                    'validators' => array(
                        array(
                            <?php if ($field['type'] == 'integer'): ?>
                            'validator'=>'Int',<?php endif; ?>
                            <?php if ($field['type'] == 'bool'): ?>
                            'validator'=>'Int',<?php endif; ?>
                            <?php if ($field['type'] == 'datetime'): ?>
                            'validator'=>'Date', 'options' => array('mm/dd/yyyy'),<?php endif; ?>
                            <?php if ($field['type'] == 'float'): ?>
                            'validator'=>'Float',<?php endif; ?>
                            <?php if ($field['type'] == 'string'): ?>
                            'validator' => 'StringLength','options' => array(0, "<?php echo (!empty($field['length'])) ? $field['length'] : 255; ?>"),
                            <?php endif; ?>
                        )
                    )
            ));
<?php endforeach; ?>
        $this->addElement('submit', 'cancel', array(
            'label'         => 'Annuler',
            'buttonType'    => \Twitter_Bootstrap_Form_Element_Submit::BUTTON_WARNING,
            'icon'          => 'cancel',
            'iconPosition'  => \Twitter_Bootstrap_Form_Element_Button::ICON_POSITION_RIGHT,
            'data-dismiss'  => 'modal'
        ));
        
        $this->addElement('submit', 'submit', array(
            'label'         => 'Envoyer',
            'buttonType'    => 'success',
            'icon'          => 'ok',
            'iconPosition'  => \Twitter_Bootstrap_Form_Element_Button::ICON_POSITION_RIGHT,
        ));

        $this->addDisplayGroup(
                array('submit', 'cancel'),
                'actions',
                array(
                        'disableLoadDefaultDecorators' => true,
                        'decorators' => array('ModalFooter')
                )
        );
    }

    public function getDataObject()
    {
        $hydrator = new <?php echo $this->entity_class; ?>Hydrator();

        return $hydrator->arrayToPopo(
            $this->getValues(),
            new <?php echo $this->entity_class; ?>DataObject()
        );
    }
}
