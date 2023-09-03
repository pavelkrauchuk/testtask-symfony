<?php

namespace App\Command;

use App\Entity\Money;
use App\MoneyTransfer;
use App\Repository\MoneyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:transfer:money',
    description: 'Переводит все денежные призы на счета пользователей в банк',
)]
class TransferMoneyCommand extends Command
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('amount', InputArgument::OPTIONAL, 'Количество передаваемых призов за итерацию')
            ->addOption(
                'simulate',
                null,
                InputOption::VALUE_NONE,
                'Только отображает количество готовых к передаче призов без их реальной отправки'
            )
            ->setHelp(<<<HELP
                Команда <info>%command.name%</info> переводит ранее не переведенные денежные призы всех пользователей
                на их счета в банке. Принимает один обязательный числовой аргумент, который определяет количество
                передаваемых призов за одну итерацию:

                <info>php %command.full_name% <amount-prizes></info>

                Использование параментра --simulate позволяет увидеть количество готовых к передаче призов без их
                реальной отправки.

                <info>php %command.full_name% --simulate</info>
                HELP
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        /** @var MoneyRepository $moneyRepository */
        $moneyRepository = $this->entityManager->getRepository(Money::class);

        if ($input->getOption('simulate')) {
            $count = $moneyRepository->getNotTransferredCount();

            $io->note(sprintf('Готовых к передаче призов: %s', $count));
        } elseif ($amount = $input->getArgument('amount')) {
            if (is_numeric($amount)) {
                $arMoneyId = array();

                while ($arMoney = $moneyRepository->getNotTransferred((int) ($amount))) {
                    foreach ($arMoney as $money) {
                        $arMoneyId[] = $money['id'];
                    }

                    MoneyTransfer::transfer($arMoney);
                    $moneyRepository->updateTransferred($arMoneyId);
                }

                if ($arMoneyId) {
                    $io->success(sprintf('Отправлено призов: %s', count($arMoneyId)));
                } else {
                    $io->note('Отсутствуют призы для отправки');
                }
            } else {
                $io->error('Аргумент <amount-prizes> должен быть целым числом');
            }
        } else {
            $io->error('Вы не указали обязательный аргумент <amount-prizes>');
        }

        return Command::SUCCESS;
    }
}
