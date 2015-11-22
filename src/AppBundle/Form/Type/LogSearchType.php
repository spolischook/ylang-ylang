<?php

namespace AppBundle\Form\Type;

use AppBundle\Entity\LogRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class LogSearchType extends AbstractType
{
    /**
     * @var LogRepository
     */
    protected $logRepository;

    /**
     * @var TokenStorage
     */
    protected $tokenStorage;

    public function __construct(LogRepository $logRepository, TokenStorage $tokenStorage)
    {
        $this->logRepository = $logRepository;
        $this->tokenStorage  = $tokenStorage;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $logFiles = $this->logRepository->getUserLogFiles();

        $builder
            ->add('search', 'search', ['required' => false])
            ->add('isRegExp', 'checkbox', ['required' => false])
            ->add('files', 'choice', [
                'multiple' => true,
                'required' => false,
                'choices'  => array_combine($logFiles, $logFiles),
                'choices_as_values' => true,
            ])
            ->add('timeIntervals', 'collection', [
                'allow_add'    => true,
                'allow_delete' => true,
                'type'         => new DateTimeIntervalType(),
            ])
            ->add('filter', 'submit')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\Form\Dto\LogSearch',
        ]);
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return "log_search_type";
    }
}
