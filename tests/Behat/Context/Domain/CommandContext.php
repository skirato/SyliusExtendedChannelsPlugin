<?php

declare(strict_types=1);

namespace Tests\MangoSylius\ExtendedChannelsPlugin\Behat\Context\Domain;

use Behat\Behat\Context\Context;
use Sylius\Bundle\CoreBundle\Command\InstallSampleDataCommand;
use Sylius\Component\Core\Formatter\StringInflector;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\HttpKernel\KernelInterface;

final class CommandContext implements Context
{
	/**
	 * @var KernelInterface
	 */
	private $kernel;

	public function __construct(
		KernelInterface $kernel
	) {
		$this->kernel = $kernel;
	}

	/**
	 * @Given I update product prices on channels :arg1 and :arg2
	 */
	public function iUpdateProductPricesOnChannelsAnd(string $arg1, string $arg2)
	{
		$application = new Application($this->kernel);
		$application->add(new InstallSampleDataCommand());
		$command = $application->find('mango:product:update-price');
		$tester = new CommandTester($command);
		$tester->execute([
			'sourceChannel' => StringInflector::nameToLowercaseCode($arg1),
			'targetChannel' => StringInflector::nameToLowercaseCode($arg2),
		]);
	}

	/**
	 * @Given I cancel orders
	 */
	public function iCancelOrders()
	{
		$application = new Application($this->kernel);
		$application->add(new InstallSampleDataCommand());
		$command = $application->find('mango:cancel-unpaid-orders');
		$tester = new CommandTester($command);
		$tester->execute([]);
	}

	/**
	 * @Given I update exchange rates
	 */
	public function iUpdateExchangeRates()
	{
		$application = new Application($this->kernel);
		$application->add(new InstallSampleDataCommand());
		$command = $application->find('mango:exchange-rates:update');
		$tester = new CommandTester($command);
		$tester->execute([
			'exchangeratesUrl' => __DIR__ . '/../../Resources/exchangeRates_%currency%.json',
		]);
	}
}
