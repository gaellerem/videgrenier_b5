<?php
namespace Tests;

use PHPUnit\Framework\TestCase;
use App\Utility\Hash;

class HashTest extends TestCase {

    public function testGenerateHashIsIdentic() {
        $string = 'password';
        $salt = 'abc123';

        $hash1 = Hash::generate($string, $salt);
        $hash2 = Hash::generate($string, $salt);

        $this->assertEquals($hash1, $hash2, 'Le hash devrait être identique avec le même salt');
    }

    public function testGenerateHashChangesWithSalt() {
        $string = 'password';

        $hash1 = Hash::generate($string, 'salt1');
        $hash2 = Hash::generate($string, 'salt2');

        $this->assertNotEquals($hash1, $hash2, 'Des salts différents doivent produire des hashs différents');
    }

    public function testGenerateUniqueProducesDifferentValues() {
        $uid1 = Hash::generateUnique();
        $uid2 = Hash::generateUnique();

        $this->assertNotEquals($uid1, $uid2, 'Les UIDs générés doivent être uniques');
    }

    public function testGenerateSaltIsRandomAndUniqueEnough() {
        $salts = [];
        $count = 1000;
        $length = 32;

        for ($i = 0; $i < $count; $i++) {
            $salt = Hash::generateSalt($length);

            // Vérifie que chaque salt a la bonne longueur
            $this->assertEquals($length, strlen($salt), "Le salt #$i a une mauvaise longueur");

            $salts[] = $salt;
        }

        // Vérifie qu'il n'y a pas trop de doublons
        $uniqueCount = count(array_unique($salts));
        $this->assertGreaterThan($count * 0.99, $uniqueCount, "Trop de doublons détectés dans les salts générés");
    }
}