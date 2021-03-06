<?php

/**
 * Test: Nette\Application\PresenterFactory.
 *
 * @author     David Grudl
 * @package    Nette\Application
 * @phpversion 5.3
 */

use Nette\Application\PresenterFactory;



require __DIR__ . '/../bootstrap.php';



$container = id(new Nette\Config\Configurator)->setTempDirectory(TEMP_DIR)->createContainer();

$factory = new PresenterFactory('base', $container);

$factory->setMapping(array(
	'Foo2' => 'App2\*\*Presenter',
));

Assert::same( 'base/presenters/FooPresenter.php', $factory->formatPresenterFile('Foo') );
Assert::same( 'base/FooModule/presenters/BarPresenter.php', $factory->formatPresenterFile('Foo:Bar') );
Assert::same( 'base/FooModule/BarModule/presenters/BazPresenter.php', $factory->formatPresenterFile('Foo:Bar:Baz') );

Assert::same( 'base/presenters/Foo2Presenter.php', $factory->formatPresenterFile('Foo2') );
Assert::same( 'base/Foo2Module/presenters/BarPresenter.php', $factory->formatPresenterFile('Foo2:Bar') );
Assert::same( 'base/Foo2Module/BarModule/presenters/BazPresenter.php', $factory->formatPresenterFile('Foo2:Bar:Baz') );



$factory->setMapping(array(
	'Foo2' => 'App2\*Presenter',
));

Assert::same( 'base/presenters/Foo2Presenter.php', $factory->formatPresenterFile('Foo2') );
Assert::same( 'base/Foo2Module/presenters/BarPresenter.php', $factory->formatPresenterFile('Foo2:Bar') );
Assert::same( 'base/Foo2Module/BarModule/presenters/BazPresenter.php', $factory->formatPresenterFile('Foo2:Bar:Baz') );
