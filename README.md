Mini ERP
Mini ERP para controle de pedidos, produtos, cupons e estoque, desenvolvido como teste técnico.
Requisitos

PHP 7.4+
MySQL
Composer
Servidor SMTP (ex.: Gmail, SendGrid)

Instalação

Clone o repositório: git clone <git@github.com:dhcostadev/mini_erp.git>
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

Boas Práticas Aplicadas
Segurança: Uso de PDO com prepared statements, sanitização de entradas, e escape de saídas (htmlspecialchars).
MVC: Separação clara entre modelos, controladores e views.
Interface: Bootstrap 5 com CSS personalizado para boa visualização.
Testes: PHPUnit cobrindo as principais funcionalidades.
Manutenção: Código modular, comentado, e com validações robustas.
Erros: Tratamento de casos como estoque insuficiente, CEP inválido, cupom expirado, e falhas de e-mail.

Testes Unitários
ProductTest: Testa criação e atualização de produtos com variações.
CartTest: Verifica adição ao carrinho, cálculo de frete, e integração com ViaCEP.
CouponTest: Valida criação e aplicação de cupons com regras de valor mínimo.

Considerações Finais
A solução atende todos os requisitos do teste, incluindo pontos extras (cupons, e-mail, webhook).
A integração com ViaCEP usa cURL e AJAX para validação em tempo real.
O envio de e-mail requer configuração de um SMTP válido.
O webhook é simples e funcional, pronto para integração.
Os testes unitários garantem robustez e confiabilidade.