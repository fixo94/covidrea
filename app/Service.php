<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Service extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    /**
     * Guarded properties.
     *
     * @var mixed
     */
    protected $guarded = [];

    /**
     * Le service appartient à un établissement
     */
    public function etablissement()
    {
        return $this->belongsTo(Etablissement::class);
    }

    /**
     * Un service a plusieurs utilisateurs
     */
    public function users() {
        return $this->belongsToMany(User::class);
    }
}
