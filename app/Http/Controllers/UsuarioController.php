<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Mostrar lista de usuarios
    public function index()
    {
        $usuarios = User::all();
        return view('usuarios.index', compact('usuarios'));
    }

    // Mostrar formulario para crear un nuevo usuario
    public function create()
    {
        return view('usuarios.create');
    }

    // Guardar un nuevo usuario
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->save();

        return redirect()->route('usuarios.index')->with('success', 'Usuario creado correctamente');
    }

    // Mostrar información de un usuario específico
    public function show($id)
    {
        $usuario = User::findOrFail($id);
        return view('usuarios.show', compact('usuario'));
    }

    // Mostrar formulario para editar un usuario
    public function edit($id)
    {
        $usuario = User::findOrFail($id);
        return view('usuarios.edit', compact('usuario'));
    }

    // Actualizar un usuario
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $usuario = User::findOrFail($id);
        $usuario->name = $request->name;
        $usuario->email = $request->email;

        if ($request->password) {
            $usuario->password = bcrypt($request->password);
        }

        $usuario->save();

        return redirect()->route('usuarios.index')->with('success', 'Usuario actualizado correctamente');
    }

    // Eliminar un usuario
    public function destroy($id)
    {
        $usuario = User::findOrFail($id);
        $usuario->delete();

        return redirect()->route('usuarios.index')->with('success', 'Usuario eliminado correctamente');
    }

    public function perfil()
    {
        return view('usuarios.perfil');
    }

    public function actualizarPerfil(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'current_password' => 'nullable|required_with:new_password',
            'new_password' => 'nullable|min:8|confirmed',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->new_password) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'La contraseña actual no es correcta']);
            }
            $user->password = Hash::make($request->new_password);
        }

        $user->save();

        return redirect()->route('usuario.perfil')->with('success', 'Perfil actualizado correctamente');
    }
}
