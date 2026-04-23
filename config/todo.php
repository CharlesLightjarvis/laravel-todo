<?php

use CharlesLightjarvis\Todo\Models\Todo;

// config for CharlesLightjarvis/Todo
return [

    /*
     * Nombre de jours après lequel les todos doivent être supprimés.
     *
     * Par défaut : 30 jours
     */
    'prune_after_days' => 30,

    /*
         * Modèles utilisés par le package.
         *
         * Vous pouvez personnaliser les modèles si vous avez besoin de le faire.
         */
    'models' => [
        'todo' => Todo::class,
    ],

    /*
         * Nom de la colonne morphique utilisée pour l'id du modèle parent.
         *
         * Par défaut : todoable_id
         * Exemple si toute l'app utilise des UUID :
         * todoable_uuid
         */
    'todo_morph_key' => 'todoable_id',
];
