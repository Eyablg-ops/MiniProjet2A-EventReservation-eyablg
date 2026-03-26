<?php
// src/Form/EventType.php
namespace App\Form;
 
use App\Entity\Event;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
 
class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre de l eventement',
                'constraints' => [new Assert\NotBlank(), new Assert\Length(['min'=>3,'max'=>255])]
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'required' => false,
                'attr' => ['rows' => 5]
            ])
            ->add('date', DateTimeType::class, [
                'label' => 'Date et heure',
                'widget' => 'single_text',  // Affiche un input datetime-local
                'constraints' => [new Assert\NotBlank()]
            ])
            ->add('location', TextType::class, [
                'label' => 'Lieu',
                'required' => false
            ])
            ->add('seats', IntegerType::class, [
                'label' => 'Nombre de places',
                'required' => false,
                'constraints' => [new Assert\Positive()]
            ])
            ->add('image', TextType::class, [
                'label' => 'URL de l image',
                'required' => false
            ])
        ;
    }
 
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
