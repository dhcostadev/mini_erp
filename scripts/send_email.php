<?php
use PHPMailer\PHPMailer\PHPMailer;
require 'vendor/autoload.php';

function sendOrderEmail($order_id, $address) {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.example.com'; // Substitua pelo seu SMTP
        $mail->SMTPAuth = true;
        $mail->Username = 'your-email@example.com'; // Substitua pelo seu e-mail
        $mail->Password = 'your-password'; // Substitua pela sua senha
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('from@example.com', 'Mini ERP');
        $mail->addAddress('customer@example.com'); // Substitua pelo e-mail do cliente

        $address_data = json_decode($address, true);
        $address_formatted = implode(', ', array_filter([
            $address_data['street'] ?? '',
            $address_data['neighborhood'] ?? '',
            $address_data['city'] ?? '',
            $address_data['state'] ?? ''
        ]));

        $mail->isHTML(true);
        $mail->Subject = 'Confirmação do Pedido #' . $order_id;
        $mail->Body = "Seu pedido #$order_id foi confirmado.<br>Endereço: " . htmlspecialchars($address_formatted);

        $mail->send();
        return ['success' => 'E-mail enviado'];
    } catch (Exception $e) {
        return ['error' => 'Erro ao enviar e-mail: ' . $mail->ErrorInfo];
    }
}
?>