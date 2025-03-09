<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250308121132 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE coupon_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE product_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE coupon (id INT NOT NULL, value INT NOT NULL, discount_type VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE product (id INT NOT NULL, name VARCHAR(255) NOT NULL, price INT NOT NULL, PRIMARY KEY(id))');

        //Добавим данных для проверки
        //Товары
        $this->addSql("INSERT INTO product (id, name, price) VALUES (1, 'Iphone', 100)");
        $this->addSql("INSERT INTO product (id, name, price) VALUES (2, 'Наушники', 20)");
        $this->addSql("INSERT INTO product (id, name, price) VALUES (3, 'Чехол', 10)");
        //Купоны
        $this->addSql("INSERT INTO coupon (id, value, discount_type) VALUES (1, 15, 'D')");
        $this->addSql("INSERT INTO coupon (id, value, discount_type) VALUES (2, 35, 'P')");
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE coupon_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE product_id_seq CASCADE');
        $this->addSql('DROP TABLE coupon');
        $this->addSql('DROP TABLE product');
    }
}
