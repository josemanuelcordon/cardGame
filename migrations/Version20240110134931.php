<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240110134931 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE card (id INT AUTO_INCREMENT NOT NULL, number INT NOT NULL, suit VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE game (id INT AUTO_INCREMENT NOT NULL, player_id INT NOT NULL, machine_pick_id INT NOT NULL, player_pick_id INT DEFAULT NULL, difficulty VARCHAR(255) NOT NULL, finished TINYINT(1) NOT NULL, won TINYINT(1) NOT NULL, INDEX IDX_232B318C99E6F5DF (player_id), INDEX IDX_232B318CF335A0CA (machine_pick_id), INDEX IDX_232B318C94BECF3F (player_pick_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE game_machine_cards (game_id INT NOT NULL, card_id INT NOT NULL, INDEX IDX_87A907E5E48FD905 (game_id), INDEX IDX_87A907E54ACC9A20 (card_id), PRIMARY KEY(game_id, card_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE game_player_cards (game_id INT NOT NULL, card_id INT NOT NULL, INDEX IDX_556017D5E48FD905 (game_id), INDEX IDX_556017D54ACC9A20 (card_id), PRIMARY KEY(game_id, card_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(180) NOT NULL, roles JSON NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE game ADD CONSTRAINT FK_232B318C99E6F5DF FOREIGN KEY (player_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE game ADD CONSTRAINT FK_232B318CF335A0CA FOREIGN KEY (machine_pick_id) REFERENCES card (id)');
        $this->addSql('ALTER TABLE game ADD CONSTRAINT FK_232B318C94BECF3F FOREIGN KEY (player_pick_id) REFERENCES card (id)');
        $this->addSql('ALTER TABLE game_machine_cards ADD CONSTRAINT FK_87A907E5E48FD905 FOREIGN KEY (game_id) REFERENCES game (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE game_machine_cards ADD CONSTRAINT FK_87A907E54ACC9A20 FOREIGN KEY (card_id) REFERENCES card (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE game_player_cards ADD CONSTRAINT FK_556017D5E48FD905 FOREIGN KEY (game_id) REFERENCES game (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE game_player_cards ADD CONSTRAINT FK_556017D54ACC9A20 FOREIGN KEY (card_id) REFERENCES card (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE game DROP FOREIGN KEY FK_232B318C99E6F5DF');
        $this->addSql('ALTER TABLE game DROP FOREIGN KEY FK_232B318CF335A0CA');
        $this->addSql('ALTER TABLE game DROP FOREIGN KEY FK_232B318C94BECF3F');
        $this->addSql('ALTER TABLE game_machine_cards DROP FOREIGN KEY FK_87A907E5E48FD905');
        $this->addSql('ALTER TABLE game_machine_cards DROP FOREIGN KEY FK_87A907E54ACC9A20');
        $this->addSql('ALTER TABLE game_player_cards DROP FOREIGN KEY FK_556017D5E48FD905');
        $this->addSql('ALTER TABLE game_player_cards DROP FOREIGN KEY FK_556017D54ACC9A20');
        $this->addSql('DROP TABLE card');
        $this->addSql('DROP TABLE game');
        $this->addSql('DROP TABLE game_machine_cards');
        $this->addSql('DROP TABLE game_player_cards');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
