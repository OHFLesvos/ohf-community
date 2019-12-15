npm update & npm run dev
for /d %i in (Modules\*) do ( pushd "%i" & npm update & npm run dev & popd )
