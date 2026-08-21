<?php

namespace App\Http\Controllers;

use App\Models\Status;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    public function index()
    {
        $teams = auth()->user()
            ->teams()
            ->withCount([
                'users',
                'tasks',
            ])
            ->latest('teams.created_at')
            ->get();

        return view(
            'teams.index',
            compact('teams')
        );
    }

    public function create()
    {
        return view('teams.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],
        ]);

        $team = Team::create([
            'name' => $validated['name'],
            'created_by' => auth()->id(),
        ]);

        $team->users()->attach(
            auth()->id()
        );

        return redirect()
            ->route('teams.show', $team)
            ->with(
                'success',
                'Team created successfully.'
            );
    }

    public function show(Team $team)
    {
        $isMember = $team->users()
            ->where(
                'users.id',
                auth()->id()
            )
            ->exists();

        if (! $isMember) {

            abort(
                403,
                'You are not a member of this team.'
            );
        }

        $team->load([
            'creator',
            'users',
            'tasks.status',
            'tasks.creator',
            'tasks.assignees',
        ]);

        $statuses = Status::orderBy(
            'order'
        )->get();

        return view(
            'teams.show',
            compact(
                'team',
                'statuses'
            )
        );
    }

    public function addMember(
        Request $request,
        Team $team
    ) {
        if (
            $team->created_by
            !==
            auth()->id()
        ) {
            abort(
                403,
                'Only the team creator can add members.'
            );
        }

        $validated = $request->validate([
            'email' => [
                'required',
                'email',
            ],
        ]);

        $user = User::where(
            'email',
            $validated['email']
        )->first();

        if (! $user) {

            return back()->withErrors([
                'email' =>
                    'This user is not registered on the platform.',
            ]);
        }

        $alreadyMember = $team->users()
            ->where(
                'users.id',
                $user->id
            )
            ->exists();

        if ($alreadyMember) {

            return back()->withErrors([
                'email' =>
                    'This user is already a member of the team.',
            ]);
        }

        $team->users()->attach(
            $user->id
        );

        return back()->with(
            'success',
            'Member added successfully.'
        );
    }
}