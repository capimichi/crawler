<?php

/**
 * Created by PhpStorm.
 * User: michelecapicchioni
 * Date: 12/07/17
 * Time: 23:19
 */

namespace Crawler\Composer;

use Composer\Composer;
use Composer\Installer\PluginInstaller;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;

class Plugin implements PluginInterface
{
    public function activate(Composer $composer, IOInterface $io)
    {
        $config = (array)$composer->getConfig()->get('config');
        if (!isset($config['bin-dir'])) {
            $binDir = $io->ask("Chose your bin-dir [bin] ", "bin");
        }

        $installer = new PluginInstaller($io, $composer);
        $composer->getInstallationManager()->addInstaller($installer);
    }

}