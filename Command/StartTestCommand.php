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

    }

    public function registerBundles()
    {

    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {

    }

    public function boot()
    {

    }

    public function shutdown()
    {

    }

    public function getBundles()
    {

    }

    public function getBundle(string $name)
    {

    }

    public function locateResource(string $name)
    {

    }

    public function getEnvironment()
    {

    }

    public function isDebug()
    {

    }

    public function getProjectDir()
    {

    }

    public function getContainer()
    {

    }

    public function getStartTime()
    {

    }

    public function getCacheDir()
    {

    }

    public function getLogDir()
    {

    }

    public function getCharset()
    {

    }

    public function __call($name, $arguments)
    {

    }
}