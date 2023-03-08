<?php

namespace App\Controller;

use App\Entity\Invoice;
use App\Entity\Subscription;
use App\Services\MailerService;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Stripe\StripeClient;
use Stripe\Exception\SignatureVerificationException;
use Stripe\Webhook;

class WebhookController extends AbstractController
{
    public function __construct(private EntityManagerInterface $entityManager) {}

    #[Route('/webhook/stripe', name: 'app_webhook_stripe')]
    public function index(LoggerInterface $logger): Response
    {
        // The Stripe library needs to be configured with your account's secret key.
        // Ensure the key is kept out of any version control system you might be using.
        $stripe = new StripeClient($_ENV['STRIPE_SK']);

        // This is your Stripe webhook secret for verifying the webhook signature.
        $endpoint_secret = $_ENV['STRIPE_WEBHOOK_SECRET'];

        $payload = @file_get_contents('php://input');
        $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
        $event = null;

        try {
            // Verify webhook signature and construct the event object
            $event = Webhook::constructEvent(
                $payload,
                $sig_header,
                $endpoint_secret
            );
        } catch(SignatureVerificationException $e) {
            // Invalid signature
            http_response_code(400);
            exit();
        }

        // Handle the event

        switch ($event->type) {
            case 'invoice.paid':
                // Save the invoice data to your database

                $invoiceEntity = new Invoice();
                $invoiceEntity->setStripeId($event->data->object->id);
                $invoiceEntity->setAmountPaid($event->data->object->amount_paid);
                $invoiceEntity->setHostedInvoiceUrl($event->data->object->customer_email);
                $invoiceEntity->setNumber($event->data->object->number);






                $this->entityManager->persist($invoiceEntity);
                $this->entityManager->flush();
              




                // Do something with the successful payment
            default:
                echo 'Received unknown event type ' . $event->type;
        }

        // Send a response to acknowledge receipt of the event
        return new Response('Success');
    }
}