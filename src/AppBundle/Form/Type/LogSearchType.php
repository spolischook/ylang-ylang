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

    /**
     * @var array
     */
    protected $rootUsers;

    public function __construct(LogRepository $logRepository, TokenStorage $tokenStorage, array $rootUsers)
    {
        $this->logRepository = $logRepository;
        $this->tokenStorage  = $tokenStorage;
        $this->rootUsers     = $rootUsers;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $logFiles = $this->logRepository->getUserLogFiles();
        $users    = $this->logRepository->getUsers();

        $builder
            ->add('search', 'search', ['required' => false])
            ->add('isRegExp', 'checkbox', ['required' => false])
            ->add('files', 'choice', [
                'multiple' => true,
                'required' => false,
                'choices'  => array_combine($logFiles, $logFiles),
            ])
            ->add('timeIntervals', 'collection', [
                'allow_add'    => true,
                'allow_delete' => true,
                'type'         => new DateTimeIntervalType(),
            ])
        ;

        if (true === in_array($this->tokenStorage->getToken()->getUser()->getUsername(), $this->rootUsers)) {
            $builder->add('users', 'choice', [
                'multiple' => true,
                'required' => false,
                'choices'  => array_combine($users, $users),
                'choices_as_values' => true,
            ]);
        }

        $builder->add('filter', 'submit')
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
