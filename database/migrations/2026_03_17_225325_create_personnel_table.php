<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('personnel', function (Blueprint $table) {
            $table->id();
            $table->string('matricule')->unique(); // EMP-0001
            $table->string('nom');
            $table->string('prenom');
            $table->date('date_naissance');
            $table->enum('sexe', ['M', 'F']);
            $table->string('telephone');
            $table->string('email')->nullable();
            $table->string('adresse')->nullable();
            $table->string('photo')->nullable();
            $table->enum('categorie', ['infirmier', 'sage_femme', 'technicien_labo', 'technicien_radio', 'aide_soignant', 'agent_accueil', 'agent_entretien', 'securite', 'administratif', 'autre']);
            $table->string('poste'); // Infirmier chef, Technicien principal, etc.
            $table->string('service')->nullable(); // Urgences, Pédiatrie, etc.
            $table->date('date_embauche');
            $table->date('date_fin_contrat')->nullable();
            $table->enum('type_contrat', ['CDI', 'CDD', 'Stage', 'Vacation'])->default('CDI');
            $table->integer('salaire')->default(0);
            $table->enum('statut', ['actif', 'conge', 'suspendu', 'demission', 'licencie'])->default('actif');
            $table->string('contact_urgence')->nullable();
            $table->string('telephone_urgence')->nullable();
            $table->text('qualifications')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('personnel');
    }
};
