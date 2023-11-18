const Ziggy = {"url":"http:\/\/localhost","port":null,"defaults":{},"routes":{"api.system-info":{"uri":"api\/system-info","methods":["GET","HEAD"]},"api.changelog":{"uri":"api\/changelog","methods":["GET","HEAD"]},"api.users.index":{"uri":"api\/users","methods":["GET","HEAD"]},"api.users.store":{"uri":"api\/users","methods":["POST"]},"api.users.show":{"uri":"api\/users\/{user}","methods":["GET","HEAD"],"parameters":["user"],"bindings":{"user":"id"}},"api.users.update":{"uri":"api\/users\/{user}","methods":["PUT","PATCH"],"parameters":["user"],"bindings":{"user":"id"}},"api.users.destroy":{"uri":"api\/users\/{user}","methods":["DELETE"],"parameters":["user"],"bindings":{"user":"id"}},"api.users.disable2FA":{"uri":"api\/users\/{user}\/disable2FA","methods":["PUT"],"parameters":["user"],"bindings":{"user":"id"}},"api.users.disableOAuth":{"uri":"api\/users\/{user}\/disableOAuth","methods":["PUT"],"parameters":["user"],"bindings":{"user":"id"}},"api.users.roles.index":{"uri":"api\/users\/{user}\/roles","methods":["GET","HEAD"],"parameters":["user"],"bindings":{"user":"id"}},"api.users.relationships.roles.index":{"uri":"api\/users\/{user}\/relationships\/roles","methods":["GET","HEAD"],"parameters":["user"],"bindings":{"user":"id"}},"api.users.relationships.roles.store":{"uri":"api\/users\/{user}\/relationships\/roles","methods":["POST"],"parameters":["user"],"bindings":{"user":"id"}},"api.users.relationships.roles.update":{"uri":"api\/users\/{user}\/relationships\/roles","methods":["PUT"],"parameters":["user"],"bindings":{"user":"id"}},"api.users.relationships.roles.destroy":{"uri":"api\/users\/{user}\/relationships\/roles","methods":["DELETE"],"parameters":["user"],"bindings":{"user":"id"}},"api.users.report.permissions":{"uri":"api\/users\/report\/permissions","methods":["GET","HEAD"]},"api.roles.permissions":{"uri":"api\/roles\/permissions","methods":["GET","HEAD"]},"api.roles.index":{"uri":"api\/roles","methods":["GET","HEAD"]},"api.roles.store":{"uri":"api\/roles","methods":["POST"]},"api.roles.show":{"uri":"api\/roles\/{role}","methods":["GET","HEAD"],"parameters":["role"],"bindings":{"role":"id"}},"api.roles.update":{"uri":"api\/roles\/{role}","methods":["PUT","PATCH"],"parameters":["role"],"bindings":{"role":"id"}},"api.roles.destroy":{"uri":"api\/roles\/{role}","methods":["DELETE"],"parameters":["role"],"bindings":{"role":"id"}},"api.roles.updateMembers":{"uri":"api\/roles\/{role}\/members","methods":["PUT"],"parameters":["role"],"bindings":{"role":"id"}},"api.roles.users.index":{"uri":"api\/roles\/{role}\/users","methods":["GET","HEAD"],"parameters":["role"],"bindings":{"role":"id"}},"api.roles.administrators.index":{"uri":"api\/roles\/{role}\/administrators","methods":["GET","HEAD"],"parameters":["role"],"bindings":{"role":"id"}},"api.roles.relationships.users.index":{"uri":"api\/roles\/{role}\/relationships\/users","methods":["GET","HEAD"],"parameters":["role"],"bindings":{"role":"id"}},"api.roles.relationships.users.store":{"uri":"api\/roles\/{role}\/relationships\/users","methods":["POST"],"parameters":["role"],"bindings":{"role":"id"}},"api.roles.relationships.users.update":{"uri":"api\/roles\/{role}\/relationships\/users","methods":["PUT"],"parameters":["role"],"bindings":{"role":"id"}},"api.roles.relationships.users.destroy":{"uri":"api\/roles\/{role}\/relationships\/users","methods":["DELETE"],"parameters":["role"],"bindings":{"role":"id"}},"api.roles.relationships.administrators.index":{"uri":"api\/roles\/{role}\/relationships\/administrators","methods":["GET","HEAD"],"parameters":["role"],"bindings":{"role":"id"}},"api.roles.relationships.administrators.store":{"uri":"api\/roles\/{role}\/relationships\/administrators","methods":["POST"],"parameters":["role"],"bindings":{"role":"id"}},"api.roles.relationships.administrators.update":{"uri":"api\/roles\/{role}\/relationships\/administrators","methods":["PUT"],"parameters":["role"],"bindings":{"role":"id"}},"api.roles.relationships.administrators.destroy":{"uri":"api\/roles\/{role}\/relationships\/administrators","methods":["DELETE"],"parameters":["role"],"bindings":{"role":"id"}},"api.roles.report.permissions":{"uri":"api\/roles\/report\/permissions","methods":["GET","HEAD"]},"api.fundraising.donors.export":{"uri":"api\/fundraising\/donors\/export","methods":["GET","HEAD"]},"api.fundraising.donors.salutations":{"uri":"api\/fundraising\/donors\/salutations","methods":["GET","HEAD"]},"api.fundraising.donors.names":{"uri":"api\/fundraising\/donors\/names","methods":["GET","HEAD"]},"api.fundraising.donors.index":{"uri":"api\/fundraising\/donors","methods":["GET","HEAD"]},"api.fundraising.donors.store":{"uri":"api\/fundraising\/donors","methods":["POST"]},"api.fundraising.donors.show":{"uri":"api\/fundraising\/donors\/{donor}","methods":["GET","HEAD"],"parameters":["donor"],"bindings":{"donor":"id"}},"api.fundraising.donors.update":{"uri":"api\/fundraising\/donors\/{donor}","methods":["PUT","PATCH"],"parameters":["donor"],"bindings":{"donor":"id"}},"api.fundraising.donors.destroy":{"uri":"api\/fundraising\/donors\/{donor}","methods":["DELETE"],"parameters":["donor"],"bindings":{"donor":"id"}},"api.fundraising.donors.vcard":{"uri":"api\/fundraising\/donors\/{donor}\/vcard","methods":["GET","HEAD"],"parameters":["donor"],"bindings":{"donor":"id"}},"api.fundraising.donors.donations.index":{"uri":"api\/fundraising\/donors\/{donor}\/donations","methods":["GET","HEAD"],"parameters":["donor"],"bindings":{"donor":"id"}},"api.fundraising.donors.donations.store":{"uri":"api\/fundraising\/donors\/{donor}\/donations","methods":["POST"],"parameters":["donor"],"bindings":{"donor":"id"}},"api.fundraising.donors.donations.export":{"uri":"api\/fundraising\/donors\/{donor}\/donations\/export","methods":["GET","HEAD"],"parameters":["donor"],"bindings":{"donor":"id"}},"api.fundraising.donors.budgets":{"uri":"api\/fundraising\/donors\/{donor}\/budgets","methods":["GET","HEAD"],"parameters":["donor"],"bindings":{"donor":"id"}},"api.fundraising.donors.comments.index":{"uri":"api\/fundraising\/donors\/{donor}\/comments","methods":["GET","HEAD"],"parameters":["donor"],"bindings":{"donor":"id"}},"api.fundraising.donors.comments.store":{"uri":"api\/fundraising\/donors\/{donor}\/comments","methods":["POST"],"parameters":["donor"],"bindings":{"donor":"id"}},"api.fundraising.donors.tags.index":{"uri":"api\/fundraising\/donors\/{donor}\/tags","methods":["GET","HEAD"],"parameters":["donor"],"bindings":{"donor":"id"}},"api.fundraising.donors.tags.store":{"uri":"api\/fundraising\/donors\/{donor}\/tags","methods":["POST"],"parameters":["donor"],"bindings":{"donor":"id"}},"api.fundraising.tags.index":{"uri":"api\/fundraising\/tags","methods":["GET","HEAD"]},"api.fundraising.donations.channels":{"uri":"api\/fundraising\/donations\/channels","methods":["GET","HEAD"]},"api.fundraising.donations.currencies":{"uri":"api\/fundraising\/donations\/currencies","methods":["GET","HEAD"]},"api.fundraising.donations.export":{"uri":"api\/fundraising\/donations\/export","methods":["GET","HEAD"]},"api.fundraising.donations.import":{"uri":"api\/fundraising\/donations\/import","methods":["POST"]},"api.fundraising.donations.index":{"uri":"api\/fundraising\/donations","methods":["GET","HEAD"]},"api.fundraising.donations.store":{"uri":"api\/fundraising\/donations","methods":["POST"]},"api.fundraising.donations.show":{"uri":"api\/fundraising\/donations\/{donation}","methods":["GET","HEAD"],"parameters":["donation"],"bindings":{"donation":"id"}},"api.fundraising.donations.update":{"uri":"api\/fundraising\/donations\/{donation}","methods":["PUT","PATCH"],"parameters":["donation"],"bindings":{"donation":"id"}},"api.fundraising.donations.destroy":{"uri":"api\/fundraising\/donations\/{donation}","methods":["DELETE"],"parameters":["donation"],"bindings":{"donation":"id"}},"api.fundraising.report.summary":{"uri":"api\/fundraising\/report\/summary","methods":["GET","HEAD"]},"api.fundraising.report.donors.count":{"uri":"api\/fundraising\/report\/donors\/count","methods":["GET","HEAD"]},"api.fundraising.report.donors.languages":{"uri":"api\/fundraising\/report\/donors\/languages","methods":["GET","HEAD"]},"api.fundraising.report.donors.countries":{"uri":"api\/fundraising\/report\/donors\/countries","methods":["GET","HEAD"]},"api.fundraising.report.donors.registrations":{"uri":"api\/fundraising\/report\/donors\/registrations","methods":["GET","HEAD"]},"api.fundraising.report.donations.registrations":{"uri":"api\/fundraising\/report\/donations\/registrations","methods":["GET","HEAD"]},"api.fundraising.report.donations.currencies":{"uri":"api\/fundraising\/report\/donations\/currencies","methods":["GET","HEAD"]},"api.fundraising.report.donations.channels":{"uri":"api\/fundraising\/report\/donations\/channels","methods":["GET","HEAD"]},"api.accounting.wallets.names":{"uri":"api\/accounting\/wallets\/names","methods":["GET","HEAD"]},"api.accounting.wallets.index":{"uri":"api\/accounting\/wallets","methods":["GET","HEAD"]},"api.accounting.wallets.create":{"uri":"api\/accounting\/wallets\/create","methods":["GET","HEAD"]},"api.accounting.wallets.store":{"uri":"api\/accounting\/wallets","methods":["POST"]},"api.accounting.wallets.show":{"uri":"api\/accounting\/wallets\/{wallet}","methods":["GET","HEAD"],"parameters":["wallet"],"bindings":{"wallet":"id"}},"api.accounting.wallets.edit":{"uri":"api\/accounting\/wallets\/{wallet}\/edit","methods":["GET","HEAD"],"parameters":["wallet"]},"api.accounting.wallets.update":{"uri":"api\/accounting\/wallets\/{wallet}","methods":["PUT","PATCH"],"parameters":["wallet"],"bindings":{"wallet":"id"}},"api.accounting.wallets.destroy":{"uri":"api\/accounting\/wallets\/{wallet}","methods":["DELETE"],"parameters":["wallet"],"bindings":{"wallet":"id"}},"api.accounting.categories.tree":{"uri":"api\/accounting\/categories\/tree","methods":["GET","HEAD"]},"api.accounting.categories.index":{"uri":"api\/accounting\/categories","methods":["GET","HEAD"]},"api.accounting.categories.create":{"uri":"api\/accounting\/categories\/create","methods":["GET","HEAD"]},"api.accounting.categories.store":{"uri":"api\/accounting\/categories","methods":["POST"]},"api.accounting.categories.show":{"uri":"api\/accounting\/categories\/{category}","methods":["GET","HEAD"],"parameters":["category"],"bindings":{"category":"id"}},"api.accounting.categories.edit":{"uri":"api\/accounting\/categories\/{category}\/edit","methods":["GET","HEAD"],"parameters":["category"]},"api.accounting.categories.update":{"uri":"api\/accounting\/categories\/{category}","methods":["PUT","PATCH"],"parameters":["category"],"bindings":{"category":"id"}},"api.accounting.categories.destroy":{"uri":"api\/accounting\/categories\/{category}","methods":["DELETE"],"parameters":["category"],"bindings":{"category":"id"}},"api.accounting.projects.tree":{"uri":"api\/accounting\/projects\/tree","methods":["GET","HEAD"]},"api.accounting.projects.index":{"uri":"api\/accounting\/projects","methods":["GET","HEAD"]},"api.accounting.projects.create":{"uri":"api\/accounting\/projects\/create","methods":["GET","HEAD"]},"api.accounting.projects.store":{"uri":"api\/accounting\/projects","methods":["POST"]},"api.accounting.projects.show":{"uri":"api\/accounting\/projects\/{project}","methods":["GET","HEAD"],"parameters":["project"],"bindings":{"project":"id"}},"api.accounting.projects.edit":{"uri":"api\/accounting\/projects\/{project}\/edit","methods":["GET","HEAD"],"parameters":["project"]},"api.accounting.projects.update":{"uri":"api\/accounting\/projects\/{project}","methods":["PUT","PATCH"],"parameters":["project"],"bindings":{"project":"id"}},"api.accounting.projects.destroy":{"uri":"api\/accounting\/projects\/{project}","methods":["DELETE"],"parameters":["project"],"bindings":{"project":"id"}},"api.accounting.transactions.summary":{"uri":"api\/accounting\/transactions\/summary","methods":["GET","HEAD"]},"api.accounting.transactions.index":{"uri":"api\/accounting\/transactions","methods":["GET","HEAD"]},"api.accounting.transactions.store":{"uri":"api\/accounting\/transactions","methods":["POST"]},"api.accounting.transactions.export":{"uri":"api\/accounting\/transactions\/export","methods":["GET","HEAD"]},"api.accounting.transactions.updateReceipt":{"uri":"api\/accounting\/transactions\/{transaction}\/receipt","methods":["POST"],"parameters":["transaction"],"bindings":{"transaction":"id"}},"api.accounting.transactions.rotateReceipt":{"uri":"api\/accounting\/transactions\/{transaction}\/receipt\/rotate","methods":["PUT"],"parameters":["transaction"],"bindings":{"transaction":"id"}},"api.accounting.transactions.history":{"uri":"api\/accounting\/transactions\/history","methods":["GET","HEAD"]},"api.accounting.transactions.transactionHistory":{"uri":"api\/accounting\/transactions\/{transaction}\/history","methods":["GET","HEAD"],"parameters":["transaction"],"bindings":{"transaction":"id"}},"api.accounting.transactions.secondaryCategories":{"uri":"api\/accounting\/transactions\/secondaryCategories","methods":["GET","HEAD"]},"api.accounting.transactions.locations":{"uri":"api\/accounting\/transactions\/locations","methods":["GET","HEAD"]},"api.accounting.transactions.costCenters":{"uri":"api\/accounting\/transactions\/costCenters","methods":["GET","HEAD"]},"api.accounting.transactions.attendees":{"uri":"api\/accounting\/transactions\/attendees","methods":["GET","HEAD"]},"api.accounting.transactions.taxonomies":{"uri":"api\/accounting\/transactions\/taxonomies","methods":["GET","HEAD"]},"api.accounting.transactions.controllable":{"uri":"api\/accounting\/transactions\/controllable","methods":["GET","HEAD"]},"api.accounting.transactions.show":{"uri":"api\/accounting\/transactions\/{transaction}","methods":["GET","HEAD"],"parameters":["transaction"],"bindings":{"transaction":"id"}},"api.accounting.transactions.update":{"uri":"api\/accounting\/transactions\/{transaction}","methods":["PUT","PATCH"],"parameters":["transaction"],"bindings":{"transaction":"id"}},"api.accounting.transactions.destroy":{"uri":"api\/accounting\/transactions\/{transaction}","methods":["DELETE"],"parameters":["transaction"],"bindings":{"transaction":"id"}},"api.accounting.transactions.undoBooking":{"uri":"api\/accounting\/transactions\/{transaction}\/undoBooking","methods":["PUT"],"parameters":["transaction"],"bindings":{"transaction":"id"}},"api.accounting.transactions.controlled":{"uri":"api\/accounting\/transactions\/{transaction}\/controlled","methods":["GET","HEAD"],"parameters":["transaction"],"bindings":{"transaction":"id"}},"api.accounting.transactions.markControlled":{"uri":"api\/accounting\/transactions\/{transaction}\/controlled","methods":["POST"],"parameters":["transaction"],"bindings":{"transaction":"id"}},"api.accounting.transactions.undoControlled":{"uri":"api\/accounting\/transactions\/{transaction}\/controlled","methods":["DELETE"],"parameters":["transaction"],"bindings":{"transaction":"id"}},"api.accounting.suppliers.export":{"uri":"api\/accounting\/suppliers\/export","methods":["GET","HEAD"]},"api.accounting.suppliers.names":{"uri":"api\/accounting\/suppliers\/names","methods":["GET","HEAD"]},"api.accounting.suppliers.index":{"uri":"api\/accounting\/suppliers","methods":["GET","HEAD"]},"api.accounting.suppliers.create":{"uri":"api\/accounting\/suppliers\/create","methods":["GET","HEAD"]},"api.accounting.suppliers.store":{"uri":"api\/accounting\/suppliers","methods":["POST"]},"api.accounting.suppliers.show":{"uri":"api\/accounting\/suppliers\/{supplier}","methods":["GET","HEAD"],"parameters":["supplier"],"bindings":{"supplier":"slug"}},"api.accounting.suppliers.edit":{"uri":"api\/accounting\/suppliers\/{supplier}\/edit","methods":["GET","HEAD"],"parameters":["supplier"]},"api.accounting.suppliers.update":{"uri":"api\/accounting\/suppliers\/{supplier}","methods":["PUT","PATCH"],"parameters":["supplier"],"bindings":{"supplier":"slug"}},"api.accounting.suppliers.destroy":{"uri":"api\/accounting\/suppliers\/{supplier}","methods":["DELETE"],"parameters":["supplier"],"bindings":{"supplier":"slug"}},"api.accounting.suppliers.transactions":{"uri":"api\/accounting\/suppliers\/{supplier}\/transactions","methods":["GET","HEAD"],"parameters":["supplier"],"bindings":{"supplier":"slug"}},"api.accounting.budgets.names":{"uri":"api\/accounting\/budgets\/names","methods":["GET","HEAD"]},"api.accounting.budgets.index":{"uri":"api\/accounting\/budgets","methods":["GET","HEAD"]},"api.accounting.budgets.store":{"uri":"api\/accounting\/budgets","methods":["POST"]},"api.accounting.budgets.show":{"uri":"api\/accounting\/budgets\/{budget}","methods":["GET","HEAD"],"parameters":["budget"],"bindings":{"budget":"id"}},"api.accounting.budgets.update":{"uri":"api\/accounting\/budgets\/{budget}","methods":["PUT","PATCH"],"parameters":["budget"],"bindings":{"budget":"id"}},"api.accounting.budgets.destroy":{"uri":"api\/accounting\/budgets\/{budget}","methods":["DELETE"],"parameters":["budget"],"bindings":{"budget":"id"}},"api.accounting.budgets.transactions":{"uri":"api\/accounting\/budgets\/{budget}\/transactions","methods":["GET","HEAD"],"parameters":["budget"],"bindings":{"budget":"id"}},"api.accounting.budgets.donations":{"uri":"api\/accounting\/budgets\/{budget}\/donations","methods":["GET","HEAD"],"parameters":["budget"],"bindings":{"budget":"id"}},"api.accounting.budgets.export":{"uri":"api\/accounting\/budgets\/{budget}\/export","methods":["GET","HEAD"],"parameters":["budget"],"bindings":{"budget":"id"}},"api.cmtyvol.languages":{"uri":"api\/cmtyvol\/languages","methods":["GET","HEAD"]},"api.cmtyvol.pickupLocations":{"uri":"api\/cmtyvol\/pickupLocations","methods":["GET","HEAD"]},"api.cmtyvol.ageDistribution":{"uri":"api\/cmtyvol\/ageDistribution","methods":["GET","HEAD"]},"api.cmtyvol.nationalityDistribution":{"uri":"api\/cmtyvol\/nationalityDistribution","methods":["GET","HEAD"]},"api.cmtyvol.genderDistribution":{"uri":"api\/cmtyvol\/genderDistribution","methods":["GET","HEAD"]},"api.cmtyvol.getHeaderMappings":{"uri":"api\/cmtyvol\/getHeaderMappings","methods":["POST"]},"api.cmtyvol.responsibilities.index":{"uri":"api\/cmtyvol\/responsibilities","methods":["GET","HEAD"]},"api.cmtyvol.responsibilities.store":{"uri":"api\/cmtyvol\/responsibilities","methods":["POST"]},"api.cmtyvol.responsibilities.show":{"uri":"api\/cmtyvol\/responsibilities\/{responsibility}","methods":["GET","HEAD"],"parameters":["responsibility"],"bindings":{"responsibility":"slug"}},"api.cmtyvol.responsibilities.update":{"uri":"api\/cmtyvol\/responsibilities\/{responsibility}","methods":["PUT","PATCH"],"parameters":["responsibility"],"bindings":{"responsibility":"slug"}},"api.cmtyvol.responsibilities.destroy":{"uri":"api\/cmtyvol\/responsibilities\/{responsibility}","methods":["DELETE"],"parameters":["responsibility"],"bindings":{"responsibility":"slug"}},"api.cmtyvol.index":{"uri":"api\/cmtyvol","methods":["GET","HEAD"]},"api.cmtyvol.store":{"uri":"api\/cmtyvol","methods":["POST"]},"api.cmtyvol.show":{"uri":"api\/cmtyvol\/{cmtyvol}","methods":["GET","HEAD"],"parameters":["cmtyvol"],"bindings":{"cmtyvol":"id"}},"api.cmtyvol.update":{"uri":"api\/cmtyvol\/{cmtyvol}","methods":["PUT","PATCH"],"parameters":["cmtyvol"],"bindings":{"cmtyvol":"id"}},"api.cmtyvol.destroy":{"uri":"api\/cmtyvol\/{cmtyvol}","methods":["DELETE"],"parameters":["cmtyvol"],"bindings":{"cmtyvol":"id"}},"api.cmtyvol.comments.index":{"uri":"api\/cmtyvol\/{cmtyvol}\/comments","methods":["GET","HEAD"],"parameters":["cmtyvol"],"bindings":{"cmtyvol":"id"}},"api.cmtyvol.comments.store":{"uri":"api\/cmtyvol\/{cmtyvol}\/comments","methods":["POST"],"parameters":["cmtyvol"],"bindings":{"cmtyvol":"id"}},"api.visitors.export":{"uri":"api\/visitors\/export","methods":["GET","HEAD"]},"api.visitors.report.visitorCheckins":{"uri":"api\/visitors\/report\/checkins","methods":["GET","HEAD"]},"api.visitors.report.genderDistribution":{"uri":"api\/visitors\/report\/genderDistribution","methods":["GET","HEAD"]},"api.visitors.report.nationalityDistribution":{"uri":"api\/visitors\/report\/nationalityDistribution","methods":["GET","HEAD"]},"api.visitors.report.ageDistribution":{"uri":"api\/visitors\/report\/ageDistribution","methods":["GET","HEAD"]},"api.visitors.report.checkInsByPurpose":{"uri":"api\/visitors\/report\/checkInsByPurpose","methods":["GET","HEAD"]},"api.visitors.checkins":{"uri":"api\/visitors\/checkins","methods":["GET","HEAD"]},"api.visitors.index":{"uri":"api\/visitors","methods":["GET","HEAD"]},"api.visitors.store":{"uri":"api\/visitors","methods":["POST"]},"api.visitors.show":{"uri":"api\/visitors\/{visitor}","methods":["GET","HEAD"],"parameters":["visitor"],"bindings":{"visitor":"id"}},"api.visitors.update":{"uri":"api\/visitors\/{visitor}","methods":["PUT"],"parameters":["visitor"],"bindings":{"visitor":"id"}},"api.visitors.destroy":{"uri":"api\/visitors\/{visitor}","methods":["DELETE"],"parameters":["visitor"],"bindings":{"visitor":"id"}},"api.visitors.checkin":{"uri":"api\/visitors\/{visitor}\/checkins","methods":["POST"],"parameters":["visitor"],"bindings":{"visitor":"id"}},"api.visitors.generateMembershipNumber":{"uri":"api\/visitors\/{visitor}\/generateMembershipNumber","methods":["POST"],"parameters":["visitor"],"bindings":{"visitor":"id"}},"api.visitors.signLiabilityForm":{"uri":"api\/visitors\/{visitor}\/signLiabilityForm","methods":["POST"],"parameters":["visitor"],"bindings":{"visitor":"id"}},"api.visitors.giveParentalConsent":{"uri":"api\/visitors\/{visitor}\/giveParentalConsent","methods":["POST"],"parameters":["visitor"],"bindings":{"visitor":"id"}},"api.badges.make":{"uri":"api\/badges\/make","methods":["POST"]},"api.badges.parseSpreadsheet":{"uri":"api\/badges\/parseSpreadsheet","methods":["POST"]},"api.badges.fetchCommunityVolunteers":{"uri":"api\/badges\/fetchCommunityVolunteers","methods":["GET","HEAD"]},"api.userprofile":{"uri":"api\/userprofile","methods":["GET","HEAD"]},"api.userprofile.update":{"uri":"api\/userprofile","methods":["POST"]},"api.userprofile.updatePassword":{"uri":"api\/userprofile\/updatePassword","methods":["POST"]},"api.userprofile.delete":{"uri":"api\/userprofile","methods":["DELETE"]},"api.userprofile.2fa.index":{"uri":"api\/userprofile\/2FA","methods":["GET","HEAD"]},"api.userprofile.2fa.store":{"uri":"api\/userprofile\/2FA","methods":["POST"]},"api.comments.show":{"uri":"api\/comments\/{comment}","methods":["GET","HEAD"],"parameters":["comment"],"bindings":{"comment":"id"}},"api.comments.update":{"uri":"api\/comments\/{comment}","methods":["PUT","PATCH"],"parameters":["comment"],"bindings":{"comment":"id"}},"api.comments.destroy":{"uri":"api\/comments\/{comment}","methods":["DELETE"],"parameters":["comment"],"bindings":{"comment":"id"}},"api.settings.fields":{"uri":"api\/settings\/fields","methods":["GET","HEAD"]},"api.settings.update":{"uri":"api\/settings","methods":["POST"]},"api.settings.reset":{"uri":"api\/settings","methods":["DELETE"]},"api.settings.resetField":{"uri":"api\/settings\/{key}","methods":["DELETE"],"parameters":["key"]},"api.countries":{"uri":"api\/countries","methods":["GET","HEAD"]},"api.localizedCountries":{"uri":"api\/localizedCountries","methods":["GET","HEAD"]},"api.localizedLanguages":{"uri":"api\/localizedLanguages","methods":["GET","HEAD"]},"api.settings":{"uri":"api\/settings","methods":["GET","HEAD"]},"login":{"uri":"login","methods":["GET","HEAD"]},"users.index":{"uri":"admin\/users","methods":["GET","HEAD"]},"users.create":{"uri":"admin\/users\/create","methods":["GET","HEAD"]},"users.show":{"uri":"admin\/users\/{user}","methods":["GET","HEAD"],"parameters":["user"]},"users.edit":{"uri":"admin\/users\/{user}\/edit","methods":["GET","HEAD"],"parameters":["user"]},"roles.index":{"uri":"admin\/roles","methods":["GET","HEAD"]},"roles.create":{"uri":"admin\/roles\/create","methods":["GET","HEAD"]},"roles.show":{"uri":"admin\/roles\/{role}","methods":["GET","HEAD"],"parameters":["role"]},"roles.edit":{"uri":"admin\/roles\/{role}\/edit","methods":["GET","HEAD"],"parameters":["role"]},"roles.manageMembers":{"uri":"admin\/roles\/{role}\/manageMembers","methods":["GET","HEAD"],"parameters":["role"]},"users.avatar":{"uri":"users\/{user}\/avatar","methods":["GET","HEAD"],"parameters":["user"],"bindings":{"user":"id"}},"accounting.webling.index":{"uri":"accounting\/wallets\/{wallet}\/webling","methods":["GET","HEAD"],"parameters":["wallet"],"bindings":{"wallet":"id"}},"cmtyvol.index":{"uri":"cmtyvol","methods":["GET","HEAD"]}}};

if (typeof window !== 'undefined' && typeof window.Ziggy !== 'undefined') {
    Object.assign(Ziggy.routes, window.Ziggy.routes);
}

export { Ziggy };
