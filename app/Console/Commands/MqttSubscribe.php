<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use PhpMqtt\Client\MqttClient;
use PhpMqtt\Client\ConnectionSettings;
use App\Models\SensorData;

class MqttSubscribe extends Command
{
    protected $signature = 'mqtt:subscribe';
    protected $description = 'Subscribe to MQTT broker (EMQX Cloud) and save sensor data';

    public function handle()
    {
        $server   = env('MQTT_HOST', 'ac5a0ac0.ala.asia-southeast1.emqxsl.com'); // alamat EMQX Cloud kamu
        $port     = env('MQTT_PORT', 8883);  // TLS port
        $clientId = env('MQTT_CLIENT_ID', 'laravel-subscriber-' . uniqid());
        $username = env('MQTT_USERNAME', 'your_mqtt_username');  // ubah sesuai EMQX
        $password = env('MQTT_PASSWORD', 'your_mqtt_password');

        $settings = (new ConnectionSettings)
            ->setUsername($username)
            ->setPassword($password)
            ->setUseTls(true)                // aktifkan TLS
            ->setTlsSelfSignedAllowed(true)  // izinkan sertifikat EMQX
            ->setTlsVerifyPeerName(false)
            ->setKeepAliveInterval(60);

        $mqtt = new MqttClient($server, $port, $clientId);

        try {
            $mqtt->connect($settings, true);
            $this->info("✅ Connected to MQTT broker ({$server}:{$port}), waiting for messages...");
        } catch (\Exception $e) {
            $this->error("❌ Failed to connect: " . $e->getMessage());
            return;
        }

        // Subscribe ke topic
        $mqtt->subscribe('sensor/data', function (string $topic, string $message) {
            $this->info("📩 Received on [$topic]: $message");

            $data = json_decode($message, true);

            if ($data) {
                SensorData::create([
                    'gas'        => $data['gas'] ?? null,
                    'suhu'       => $data['suhu'] ?? null,
                    'kelembaban' => $data['kelembaban'] ?? null,
                ]);
            }
        }, 0);

        $mqtt->loop(true);
    }
}
