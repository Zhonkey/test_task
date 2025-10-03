<?php

use yii\db\Migration;

class m130524_201442_init extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // https://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey(),
            'username' => $this->string(),
            'password_hash' => $this->integer()->notNull(),

            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
        ], $tableOptions);

        $this->createTable('{{%book}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(255)->notNull(),
            'year' => $this->integer()->notNull(),
            'description' => $this->text(),
            'isbn' => $this->string(17),
            'cover' => $this->string(),

            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime()->notNull(),
            'created_by' => $this->integer()->notNull(),
            'updated_by' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->addForeignKey(
            'fk_book_created_by',
            '{{%book}}',
            'created_by',
            '{{%user}}',
            'id'
        );
        $this->addForeignKey(
            'fk_book_updated_by',
            '{{%book}}',
            'updated_by',
            '{{%user}}',
            'id'
        );

        $this->createTable('{{%author}}', [
            'id' => $this->primaryKey(),
            'first_name' => $this->string(255),
            'last_name' => $this->string(255),
            'surname' => $this->string(255),

            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
            'created_by' => $this->integer()->notNull(),
            'updated_by' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->addForeignKey(
            'fk_author_created_by',
            '{{%author}}',
            'created_by',
            '{{%user}}',
            'id'
        );
        $this->addForeignKey(
            'fk_author_updated_by',
            '{{%author}}',
            'updated_by',
            '{{%user}}',
            'id'
        );

        $this->createTable('{{%book_author}}', [
            'id' => $this->primaryKey(),
            'book_id' => $this->integer()->notNull(),
            'author_id' => $this->integer()->notNull(),

            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime()->notNull(),
            'created_by' => $this->integer()->notNull(),
            'updated_by' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->addForeignKey(
            'fk_book_author_book',
            '{{%book_author}}',
            'book_id',
            '{{%book}}',
            'id'
        );

        $this->addForeignKey(
            'fk_book_author_author',
            '{{%book_author}}',
            'author_id',
            '{{%author}}',
            'id'
        );

        $this->addForeignKey(
            'fk_book_author_created_by',
            '{{%book_author}}',
            'created_by',
            '{{%user}}',
            'id'
        );
        $this->addForeignKey(
            'fk_book_author_updated_by',
            '{{%book_author}}',
            'updated_by',
            '{{%user}}',
            'id'
        );

        $this->createTable('{{%subscriber}}', [
            'id' => $this->primaryKey(),
            'phone' => $this->string(),

            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
        ], $tableOptions);

        $this->createTable('{{%subscription}}', [
            'id' => $this->primaryKey(),
            'subscriber_id' => $this->integer()->notNull(),
            'author_id' => $this->integer()->notNull(),

            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
        ], $tableOptions);

        $this->addForeignKey(
            'fk_subscription_author',
            '{{%subscription}}',
            'author_id',
            '{{%author}}',
            'id'
        );

        $this->addForeignKey(
            'fk_subscription_subscriber',
            '{{%subscription}}',
            'subscriber_id',
            '{{%subscriber}}',
            'id'
        );

        $this->createTable('{{%notification}}', [
            'id' => $this->primaryKey(),
            'subscription_id' => $this->integer(),
            'book_id' => $this->integer(),
            'is_success' => $this->boolean()->notNull(),

            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
        ], $tableOptions);

        $this->addForeignKey(
            'fk_notification_subscription',
            '{{%notification}}',
            'subscription_id',
            '{{%subscription}}',
            'id'
        );

        $this->addForeignKey(
            'fk_notification_book',
            '{{%notification}}',
            'book_id',
            '{{%book}}',
            'id'
        );
    }

    public function down()
    {
        $this->droPForeignKey(
            'fk_notification_book',
            '{{%notification}}',
        );
        $this->dropForeignKey(
            'fk_notification_subscription',
            '{{%notification}}',
        );
        $this->dropTable('{{%notification}}');

        $this->dropForeignKey(
            'fk_subscription_author',
            '{{%subscription}}',
        );
        $this->dropForeignKey(
            'fk_subscription_subscriber',
            '{{%subscription}}',
        );
        $this->dropTable('{{%subscription}}');
        $this->dropTable('{{%subscriber}}');

        $this->dropForeignKey(
            'fk_book_author_author',
            '{{%book_author}}',
        );
        $this->dropForeignKey(
            'fk_book_author_book',
            '{{%book_author}}',
        );

        $this->dropForeignKey(
            'fk_book_author_created_by',
            '{{%book_author}}'
        );
        $this->dropForeignKey(
            'fk_book_author_updated_by',
            '{{%book_author}}'
        );

        $this->dropTable('{{%book_author}}');

        $this->dropForeignKey(
            'fk_author_created_by',
            '{{%author}}'
        );
        $this->dropForeignKey(
            'fk_author_updated_by',
            '{{%author}}'
        );

        $this->dropTable('{{%author}}');

        $this->dropForeignKey(
            'fk_book_created_by',
            '{{%book}}'
        );
        $this->dropForeignKey(
            'fk_book_updated_by',
            '{{%book}}'
        );

        $this->dropTable('{{%book}}');

        $this->dropTable('{{%user}}');
    }
}
