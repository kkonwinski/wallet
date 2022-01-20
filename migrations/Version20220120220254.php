<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220120220254 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE transaction (id INT AUTO_INCREMENT NOT NULL, wallet_id INT NOT NULL, type_transaction_id INT DEFAULT NULL, amount DOUBLE PRECISION NOT NULL, created_at DATETIME NOT NULL, amount_before DOUBLE PRECISION DEFAULT NULL, INDEX IDX_723705D1712520F3 (wallet_id), INDEX IDX_723705D17903E29B (type_transaction_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type_transaction (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE wallet (id INT AUTO_INCREMENT NOT NULL, balance DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D1712520F3 FOREIGN KEY (wallet_id) REFERENCES wallet (id)');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D17903E29B FOREIGN KEY (type_transaction_id) REFERENCES type_transaction (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE transaction DROP FOREIGN KEY FK_723705D17903E29B');
        $this->addSql('ALTER TABLE transaction DROP FOREIGN KEY FK_723705D1712520F3');
        $this->addSql('DROP TABLE transaction');
        $this->addSql('DROP TABLE type_transaction');
        $this->addSql('DROP TABLE wallet');
    }
}
