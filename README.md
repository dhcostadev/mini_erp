Mini ERP
Mini ERP para controle de pedidos, produtos, cupons e estoque, desenvolvido como teste técnico.
Requisitos

PHP 7.4+
MySQL
Composer
Servidor SMTP (ex.: Gmail, SendGrid)

Instalação

Clone o repositório: git clone <URL>
Importe o banco de dados: mysql -u root -p < database.sql
Configure o banco em config/database.php
Instale dependências: composer install
Configure o SMTP em scripts/send_email.php
Inicie o servidor: php -S localhost:8000 -t public

Funcionalidades

Cadastro, edição e listagem de produtos com variações e estoque.
Carrinho com cálculo de frete e integração com ViaCEP.
Gerenciamento de cupons com validade e valor mínimo.
Finalização de pedido com envio de e-mail.
Webhook para atualizar/remover pedidos.
Testes unitários com PHPUnit.

Executar Testes
vendor/bin/phpunit tests

Endpoints

Produtos: /products/index.php
Carrinho: /cart/index.php
Cupons: /coupons/index.php
Webhook: /webhook.php (POST com JSON { "order_id": 1, "status": "completed" })

Observações

Configure um servidor SMTP válido para envio de e-mails.
Teste o webhook com ferramentas como Postman.
