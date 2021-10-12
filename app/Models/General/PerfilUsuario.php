<?php

namespace App\Models\General;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;



/**
 * App\Models\General\PerfilUsuario
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\User[] $usuarios
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\General\PerfilUsuario query()
 * @mixin \Eloquent
 * @property int $id
 * @property string $nome
 * @property string $descricao
 * @property int $super
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\General\PerfilUsuario whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\General\PerfilUsuario whereDescricao($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\General\PerfilUsuario whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\General\PerfilUsuario whereNome($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\General\PerfilUsuario whereSuper($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\General\PerfilUsuario whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\General\PerfilUsuario newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\General\PerfilUsuario newQuery()
 */
class PerfilUsuario extends Model
{
    protected $table = 'perfil_usuario';

    public function usuarios()
    {

        return $this->hasMany(User::class, 'perfil_id', 'id');

    }





}
