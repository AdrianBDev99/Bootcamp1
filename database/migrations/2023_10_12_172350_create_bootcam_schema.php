<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */

    //Estas constantes sirven para que al cambiar nombres sea mas sencillo
    const CLIENTS = "clients",
        BUSINESSES = "businesses",
        FAVORITES = "favorites",
        IMAGES = "images";

    const CLIENT_ID = "client_id",
        BUSINESS_ID = "bussines_id";

    //Aqui se suben los cambios, es la funcion principal para subir cambios
    public function up(): void
    {
        self:: createTables();
        self::createPolyTables();
    }

    public function createPolyTables()
    {
        self::createImagesTable();
    }

    public function createTables()
    {
self::createClientsTable();
self::createBusinessTable();
self::createfovoritesTable();
    }


    public function createClientsTable()
    {
        //Crear funcion con nombre CLIENTS de tipo table
        Schema::create(self::CLIENTS, function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('phone');
            $table->boolean('active');
            //para saber cueando fue creada
            $table->timestamps();
        });
    }

    public function createBusinessTable()
    {
        Schema::create(self::BUSINESSES, function (Blueprint $table) {
            $table->id();
            $table->string('name');
            //unique permite que no se repitan los emails
            $table->string('email')->unique();
            $table->string('password');
            $table->string('phone');
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    public function createfovoritesTable()
    {
        Schema::create(self::FAVORITES, function (Blueprint $table) {
            $table->id();
            //Foreing son id de referencia a otras tablas
            $table->foreignId(self::CLIENT_ID)->constrained(self::CLIENTS);
            $table->foreignId(self::BUSINESS_ID)->constrained(self::BUSINESSES);
            //Para ser una tabala de pivote(para relacionar otras tablas) no se pone timestamps
        });
    }

    public function createImagesTable()
    {
        Schema::create(self::IMAGES, function (Blueprint $table) {
            $table->id();
            $table->string('url');
            //El tipo morphs da la tabla y el id del dato dentro de la tabla para acceder a sus datos
            $table->morphs('imageable');
            $table->timestamps();
        });
    }

    //Sirve para hacer rollback de las tablas. Se hace en sentido inverso a como fueron creadas
    public function down(): void
    {
        $polyTables = [
            self::IMAGES,
        ];

        $tables = [
            self::FAVORITES,
            self::BUSINESSES,
            self::CLIENTS,
        ];

        foreach ($polyTables as $table) {
            Schema::dropIfExists($table);
        }
        foreach ($tables as $table) {
            Schema::dropIfExists($table);
        }
    }
};


