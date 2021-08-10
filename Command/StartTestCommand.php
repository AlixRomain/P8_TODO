<?php
namespace App\Command;
use App\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * @method string getBuildDir()
 */
class CreateUserCommand extends Command implements KernelInterface
{
    protected static $defaultName = 'app:start-test';
    private $manager;
    public function __construct(ObjectManager $manager)
    {
        $this->manager = $manager;
        parent::__construct();
    }
    protected function configure()
    {
        $this
            ->setDescription('Command for start test')
            ->addArgument('firstName', InputArgument::REQUIRED, 'User firstname')
            ->addArgument('lastName', InputArgument::OPTIONAL, 'User lastname optional')
        ;
    }
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln([
                             'Command Start Test',
                             '============'
                         ]);


        $output->writeln('Successful you have restart the DB : todilist-test_test');
        return 0;
    }

    public function handle(Request $request, int $type = self::MAIN_REQUEST, bool $catch = true)
    {
        // TODO: Implement handle() method.
    }

    public function registerBundles()
    {
        // TODO: Implement registerBundles() method.
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        // TODO: Implement registerContainerConfiguration() method.
    }

    public function boot()
    {
        // TODO: Implement boot() method.
    }

    public function shutdown()
    {
        // TODO: Implement shutdown() method.
    }

    public function getBundles()
    {
        // TODO: Implement getBundles() method.
    }

    public function getBundle(string $name)
    {
        // TODO: Implement getBundle() method.
    }

    public function locateResource(string $name)
    {
        // TODO: Implement locateResource() method.
    }

    public function getEnvironment()
    {
        // TODO: Implement getEnvironment() method.
    }

    public function isDebug()
    {
        // TODO: Implement isDebug() method.
    }

    public function getProjectDir()
    {
        // TODO: Implement getProjectDir() method.
    }

    public function getContainer()
    {
        // TODO: Implement getContainer() method.
    }

    public function getStartTime()
    {
        // TODO: Implement getStartTime() method.
    }

    public function getCacheDir()
    {
        // TODO: Implement getCacheDir() method.
    }

    public function getLogDir()
    {
        // TODO: Implement getLogDir() method.
    }

    public function getCharset()
    {
        // TODO: Implement getCharset() method.
    }

    public function __call($name, $arguments)
    {
        // TODO: Implement @method string getBuildDir()
    }
}