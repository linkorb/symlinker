<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Yaml\Yaml;
use RuntimeException;

#[AsCommand(
    name: 'link',
    description: 'Add a short description for your command',
)]
class LinkCommand extends Command
{
    protected function configure(): void
    {
        $this
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $archivePath = '/tmp/symlinker/archive';

        if (!file_exists($archivePath)) {
            mkdir($archivePath, 0777, true);
        }
        
        $basepath = rtrim(getcwd(), '/');
        $filename = $basepath .'/symlinker.yaml';
        if (!file_exists($filename)) {
            throw new RuntimeException('File not found: ' . $filename);
        }
        $yaml = file_get_contents($filename);
        $config = Yaml::parse($yaml, true);

        foreach ($config['links'] ?? [] as $k=>$v) {
            if (is_string($v)) {
                $part = explode(':', $v);
                if (count($part)!=2) {
                    throw new RuntimeException("Link string expected two parts. received: " . $v);
                }
                $from = $part[0];
                $to = $part[1];
            } elseif (is_array($v)) {
                $from = $v['from'] ?? null;
                $to = $v['to'] ?? null;
            }

            if (!$from) {
                throw new RuntimeException("link.from not resolved");
            }
            if (!$to) {
                throw new RuntimeException("link.to not resolved");
            }

            if (substr($from, 0, 2)=='~/') {
                $from = getenv("HOME") . substr($from, 1);
            }

            if (substr($from, 0, 2)=='./') {
                $from = $basepath . substr($from, 1);
            }
            if (substr($to, 0, 2)=='./') {
                $to = $basepath . substr($to, 1);
            }

            if (!file_exists($from)) {
                throw new RuntimeException("link.from not found: " . $from);
            }
            $io->writeln("🔗 Linking <info>" . $from . '</info> to <info>' . $to . '</info>');

            if (file_exists($to)) {

                if (is_link($to)) {
                    // symlink already exists, safe to remove to ensure it's correctly added afterwards
                    $io->writeln('  💬 Symlink already exists - removing ' . $to);
                    unlink($to);
                } elseif (is_dir($to) || is_file($to)) {
                    // file or directory already exists? move it into the archive instead of deleting
                    $archiveName = realpath ($to);
                    $archiveName = str_replace('/',  '__', $archiveName);
                    $archiveName .= '.' . date('Ymd.His');
                    // throw new RuntimeException("link.to already exists as a directory");
                    $io->writeln('  📦 Archiving existing target as ' . $archiveName);
                    rename($to, $archivePath . '/' . $archiveName);
                    // exit();
                }
            }

            // from exists, and to does not. create the symlink:
            symlink($from, $to);
            $io->writeln('  ✅ Symlink created');

        }
        // print_r($config);
        // $arg1 = $input->getArgument('arg1');



        // if ($arg1) {
        //     $io->note(sprintf('You passed an argument: %s', $arg1));
        // }

        // if ($input->getOption('option1')) {
        //     // ...
        // }

        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');

        return Command::SUCCESS;
    }
}
