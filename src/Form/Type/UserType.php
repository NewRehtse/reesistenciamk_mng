<?php


namespace App\Form\Type;


use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Esther Ibáñez González <eibanez@ces.vocento.com>
 */
class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, ['label' => 'E-Mail *'])
            ->add('password', RepeatedType::class,  [
                'type' => PasswordType::class,
                'first_options'  => array('label' => 'Contraseña *'),
                'second_options' => array('label' => 'Repite Contraseña *'),
            ])
            ->add('nick', TextType::class, ['required'=> false, 'label' => 'Nick'])
            ->add('address', TextType::class, ['required' => false, 'label' => 'Dirección'])
            ->add('city', TextType::class, ['required' => false, 'label' => 'Ciudad'])
            ->add('postalCode', TextType::class, ['required' => false, 'label' => 'Código Postal'])
            ->add('phoneNumber', TextType::class, ['required' => false, 'label' => 'Teléfono'])
            ->add('nickTelegram', TextType::class, ['required' => false, 'label' => 'Nick telegram'])
            ->add('save', SubmitType::class, ['label' => 'Registrarse', 'attr' => ['class' => 'login100-form-btn']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => User::class,
        ));
    }
}