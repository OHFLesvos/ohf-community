const Ziggy = {"url":"http:\/\/ohf.test","port":null,"defaults":{},"routes":{"api.users.index":{"uri":"api\/users","methods":["GET","HEAD"]},"api.users.store":{"uri":"api\/users","methods":["POST"]},"api.users.show":{"uri":"api\/users\/{user}","methods":["GET","HEAD"],"bindings":{"user":"id"}},"api.users.update":{"uri":"api\/users\/{user}","methods":["PUT","PATCH"],"bindings":{"user":"id"}},"api.users.destroy":{"uri":"api\/users\/{user}","methods":["DELETE"],"bindings":{"user":"id"}},"api.users.roles.index":{"uri":"api\/users\/{user}\/roles","methods":["GET","HEAD"],"bindings":{"user":"id"}},"api.users.relationships.roles.index":{"uri":"api\/users\/{user}\/relationships\/roles","methods":["GET","HEAD"],"bindings":{"user":"id"}},"api.users.relationships.roles.store":{"uri":"api\/users\/{user}\/relationships\/roles","methods":["POST"],"bindings":{"user":"id"}},"api.users.relationships.roles.update":{"uri":"api\/users\/{user}\/relationships\/roles","methods":["PUT"],"bindings":{"user":"id"}},"api.users.relationships.roles.destroy":{"uri":"api\/users\/{user}\/relationships\/roles","methods":["DELETE"],"bindings":{"user":"id"}},"api.roles.index":{"uri":"api\/roles","methods":["GET","HEAD"]},"api.roles.store":{"uri":"api\/roles","methods":["POST"]},"api.roles.show":{"uri":"api\/roles\/{role}","methods":["GET","HEAD"],"bindings":{"role":"id"}},"api.roles.update":{"uri":"api\/roles\/{role}","methods":["PUT","PATCH"],"bindings":{"role":"id"}},"api.roles.destroy":{"uri":"api\/roles\/{role}","methods":["DELETE"],"bindings":{"role":"id"}},"api.roles.users.index":{"uri":"api\/roles\/{role}\/users","methods":["GET","HEAD"],"bindings":{"role":"id"}},"api.roles.administrators.index":{"uri":"api\/roles\/{role}\/administrators","methods":["GET","HEAD"],"bindings":{"role":"id"}},"api.roles.relationships.users.index":{"uri":"api\/roles\/{role}\/relationships\/users","methods":["GET","HEAD"],"bindings":{"role":"id"}},"api.roles.relationships.users.store":{"uri":"api\/roles\/{role}\/relationships\/users","methods":["POST"],"bindings":{"role":"id"}},"api.roles.relationships.users.update":{"uri":"api\/roles\/{role}\/relationships\/users","methods":["PUT"],"bindings":{"role":"id"}},"api.roles.relationships.users.destroy":{"uri":"api\/roles\/{role}\/relationships\/users","methods":["DELETE"],"bindings":{"role":"id"}},"api.roles.relationships.administrators.index":{"uri":"api\/roles\/{role}\/relationships\/administrators","methods":["GET","HEAD"],"bindings":{"role":"id"}},"api.roles.relationships.administrators.store":{"uri":"api\/roles\/{role}\/relationships\/administrators","methods":["POST"],"bindings":{"role":"id"}},"api.roles.relationships.administrators.update":{"uri":"api\/roles\/{role}\/relationships\/administrators","methods":["PUT"],"bindings":{"role":"id"}},"api.roles.relationships.administrators.destroy":{"uri":"api\/roles\/{role}\/relationships\/administrators","methods":["DELETE"],"bindings":{"role":"id"}},"api.fundraising.donors.export":{"uri":"api\/fundraising\/donors\/export","methods":["GET","HEAD"]},"api.fundraising.donors.salutations":{"uri":"api\/fundraising\/donors\/salutations","methods":["GET","HEAD"]},"api.fundraising.donors.names":{"uri":"api\/fundraising\/donors\/names","methods":["GET","HEAD"]},"api.fundraising.donors.index":{"uri":"api\/fundraising\/donors","methods":["GET","HEAD"]},"api.fundraising.donors.store":{"uri":"api\/fundraising\/donors","methods":["POST"]},"api.fundraising.donors.show":{"uri":"api\/fundraising\/donors\/{donor}","methods":["GET","HEAD"],"bindings":{"donor":"id"}},"api.fundraising.donors.update":{"uri":"api\/fundraising\/donors\/{donor}","methods":["PUT","PATCH"],"bindings":{"donor":"id"}},"api.fundraising.donors.destroy":{"uri":"api\/fundraising\/donors\/{donor}","methods":["DELETE"],"bindings":{"donor":"id"}},"api.fundraising.donors.vcard":{"uri":"api\/fundraising\/donors\/{donor}\/vcard","methods":["GET","HEAD"],"bindings":{"donor":"id"}},"api.fundraising.donors.donations.index":{"uri":"api\/fundraising\/donors\/{donor}\/donations","methods":["GET","HEAD"],"bindings":{"donor":"id"}},"api.fundraising.donors.donations.store":{"uri":"api\/fundraising\/donors\/{donor}\/donations","methods":["POST"],"bindings":{"donor":"id"}},"api.fundraising.donors.donations.export":{"uri":"api\/fundraising\/donors\/{donor}\/donations\/export","methods":["GET","HEAD"],"bindings":{"donor":"id"}},"api.fundraising.donors.budgets":{"uri":"api\/fundraising\/donors\/{donor}\/budgets","methods":["GET","HEAD"],"bindings":{"donor":"id"}},"api.fundraising.donors.comments.index":{"uri":"api\/fundraising\/donors\/{donor}\/comments","methods":["GET","HEAD"],"bindings":{"donor":"id"}},"api.fundraising.donors.comments.store":{"uri":"api\/fundraising\/donors\/{donor}\/comments","methods":["POST"],"bindings":{"donor":"id"}},"api.fundraising.donors.tags.index":{"uri":"api\/fundraising\/donors\/{donor}\/tags","methods":["GET","HEAD"],"bindings":{"donor":"id"}},"api.fundraising.donors.tags.store":{"uri":"api\/fundraising\/donors\/{donor}\/tags","methods":["POST"],"bindings":{"donor":"id"}},"api.fundraising.tags.index":{"uri":"api\/fundraising\/tags","methods":["GET","HEAD"]},"api.fundraising.donations.channels":{"uri":"api\/fundraising\/donations\/channels","methods":["GET","HEAD"]},"api.fundraising.donations.currencies":{"uri":"api\/fundraising\/donations\/currencies","methods":["GET","HEAD"]},"api.fundraising.donations.export":{"uri":"api\/fundraising\/donations\/export","methods":["GET","HEAD"]},"api.fundraising.donations.import":{"uri":"api\/fundraising\/donations\/import","methods":["POST"]},"api.fundraising.donations.index":{"uri":"api\/fundraising\/donations","methods":["GET","HEAD"]},"api.fundraising.donations.store":{"uri":"api\/fundraising\/donations","methods":["POST"]},"api.fundraising.donations.show":{"uri":"api\/fundraising\/donations\/{donation}","methods":["GET","HEAD"],"bindings":{"donation":"id"}},"api.fundraising.donations.update":{"uri":"api\/fundraising\/donations\/{donation}","methods":["PUT","PATCH"],"bindings":{"donation":"id"}},"api.fundraising.donations.destroy":{"uri":"api\/fundraising\/donations\/{donation}","methods":["DELETE"],"bindings":{"donation":"id"}},"api.fundraising.report.donors.count":{"uri":"api\/fundraising\/report\/donors\/count","methods":["GET","HEAD"]},"api.fundraising.report.donors.languages":{"uri":"api\/fundraising\/report\/donors\/languages","methods":["GET","HEAD"]},"api.fundraising.report.donors.countries":{"uri":"api\/fundraising\/report\/donors\/countries","methods":["GET","HEAD"]},"api.fundraising.report.donors.registrations":{"uri":"api\/fundraising\/report\/donors\/registrations","methods":["GET","HEAD"]},"api.fundraising.report.donations.registrations":{"uri":"api\/fundraising\/report\/donations\/registrations","methods":["GET","HEAD"]},"api.fundraising.report.donations.currencies":{"uri":"api\/fundraising\/report\/donations\/currencies","methods":["GET","HEAD"]},"api.fundraising.report.donations.channels":{"uri":"api\/fundraising\/report\/donations\/channels","methods":["GET","HEAD"]},"api.comments.show":{"uri":"api\/comments\/{comment}","methods":["GET","HEAD"],"bindings":{"comment":"id"}},"api.comments.update":{"uri":"api\/comments\/{comment}","methods":["PUT","PATCH"],"bindings":{"comment":"id"}},"api.comments.destroy":{"uri":"api\/comments\/{comment}","methods":["DELETE"],"bindings":{"comment":"id"}},"api.accounting.wallets.names":{"uri":"api\/accounting\/wallets\/names","methods":["GET","HEAD"]},"api.accounting.wallets.index":{"uri":"api\/accounting\/wallets","methods":["GET","HEAD"]},"api.accounting.wallets.create":{"uri":"api\/accounting\/wallets\/create","methods":["GET","HEAD"]},"api.accounting.wallets.store":{"uri":"api\/accounting\/wallets","methods":["POST"]},"api.accounting.wallets.show":{"uri":"api\/accounting\/wallets\/{wallet}","methods":["GET","HEAD"],"bindings":{"wallet":"id"}},"api.accounting.wallets.edit":{"uri":"api\/accounting\/wallets\/{wallet}\/edit","methods":["GET","HEAD"]},"api.accounting.wallets.update":{"uri":"api\/accounting\/wallets\/{wallet}","methods":["PUT","PATCH"],"bindings":{"wallet":"id"}},"api.accounting.wallets.destroy":{"uri":"api\/accounting\/wallets\/{wallet}","methods":["DELETE"],"bindings":{"wallet":"id"}},"api.accounting.categories.tree":{"uri":"api\/accounting\/categories\/tree","methods":["GET","HEAD"]},"api.accounting.categories.index":{"uri":"api\/accounting\/categories","methods":["GET","HEAD"]},"api.accounting.categories.create":{"uri":"api\/accounting\/categories\/create","methods":["GET","HEAD"]},"api.accounting.categories.store":{"uri":"api\/accounting\/categories","methods":["POST"]},"api.accounting.categories.show":{"uri":"api\/accounting\/categories\/{category}","methods":["GET","HEAD"],"bindings":{"category":"id"}},"api.accounting.categories.edit":{"uri":"api\/accounting\/categories\/{category}\/edit","methods":["GET","HEAD"]},"api.accounting.categories.update":{"uri":"api\/accounting\/categories\/{category}","methods":["PUT","PATCH"],"bindings":{"category":"id"}},"api.accounting.categories.destroy":{"uri":"api\/accounting\/categories\/{category}","methods":["DELETE"],"bindings":{"category":"id"}},"api.accounting.projects.tree":{"uri":"api\/accounting\/projects\/tree","methods":["GET","HEAD"]},"api.accounting.projects.index":{"uri":"api\/accounting\/projects","methods":["GET","HEAD"]},"api.accounting.projects.create":{"uri":"api\/accounting\/projects\/create","methods":["GET","HEAD"]},"api.accounting.projects.store":{"uri":"api\/accounting\/projects","methods":["POST"]},"api.accounting.projects.show":{"uri":"api\/accounting\/projects\/{project}","methods":["GET","HEAD"],"bindings":{"project":"id"}},"api.accounting.projects.edit":{"uri":"api\/accounting\/projects\/{project}\/edit","methods":["GET","HEAD"]},"api.accounting.projects.update":{"uri":"api\/accounting\/projects\/{project}","methods":["PUT","PATCH"],"bindings":{"project":"id"}},"api.accounting.projects.destroy":{"uri":"api\/accounting\/projects\/{project}","methods":["DELETE"],"bindings":{"project":"id"}},"api.accounting.transactions.summary":{"uri":"api\/accounting\/transactions\/summary","methods":["GET","HEAD"]},"api.accounting.transactions.index":{"uri":"api\/accounting\/transactions","methods":["GET","HEAD"]},"api.accounting.transactions.store":{"uri":"api\/accounting\/transactions","methods":["POST"]},"api.accounting.transactions.export":{"uri":"api\/accounting\/wallets\/{wallet}\/transactions\/export","methods":["GET","HEAD"]},"api.accounting.transactions.updateReceipt":{"uri":"api\/accounting\/transactions\/{transaction}\/receipt","methods":["POST"],"bindings":{"transaction":"id"}},"api.accounting.transactions.rotateReceipt":{"uri":"api\/accounting\/transactions\/{transaction}\/receipt\/rotate","methods":["PUT"],"bindings":{"transaction":"id"}},"api.accounting.transactions.history":{"uri":"api\/accounting\/transactions\/history","methods":["GET","HEAD"]},"api.accounting.transactions.transactionHistory":{"uri":"api\/accounting\/transactions\/{transaction}\/history","methods":["GET","HEAD"],"bindings":{"transaction":"id"}},"api.accounting.transactions.secondaryCategories":{"uri":"api\/accounting\/transactions\/secondaryCategories","methods":["GET","HEAD"]},"api.accounting.transactions.locations":{"uri":"api\/accounting\/transactions\/locations","methods":["GET","HEAD"]},"api.accounting.transactions.costCenters":{"uri":"api\/accounting\/transactions\/costCenters","methods":["GET","HEAD"]},"api.accounting.transactions.attendees":{"uri":"api\/accounting\/transactions\/attendees","methods":["GET","HEAD"]},"api.accounting.transactions.taxonomies":{"uri":"api\/accounting\/transactions\/taxonomies","methods":["GET","HEAD"]},"api.accounting.transactions.controllable":{"uri":"api\/accounting\/transactions\/controllable","methods":["GET","HEAD"]},"api.accounting.transactions.show":{"uri":"api\/accounting\/transactions\/{transaction}","methods":["GET","HEAD"],"bindings":{"transaction":"id"}},"api.accounting.transactions.update":{"uri":"api\/accounting\/transactions\/{transaction}","methods":["PUT","PATCH"],"bindings":{"transaction":"id"}},"api.accounting.transactions.destroy":{"uri":"api\/accounting\/transactions\/{transaction}","methods":["DELETE"],"bindings":{"transaction":"id"}},"api.accounting.transactions.undoBooking":{"uri":"api\/accounting\/transactions\/{transaction}\/undoBooking","methods":["PUT"],"bindings":{"transaction":"id"}},"api.accounting.transactions.controlled":{"uri":"api\/accounting\/transactions\/{transaction}\/controlled","methods":["GET","HEAD"],"bindings":{"transaction":"id"}},"api.accounting.transactions.markControlled":{"uri":"api\/accounting\/transactions\/{transaction}\/controlled","methods":["POST"],"bindings":{"transaction":"id"}},"api.accounting.transactions.undoControlled":{"uri":"api\/accounting\/transactions\/{transaction}\/controlled","methods":["DELETE"],"bindings":{"transaction":"id"}},"api.accounting.suppliers.export":{"uri":"api\/accounting\/suppliers\/export","methods":["GET","HEAD"]},"api.accounting.suppliers.names":{"uri":"api\/accounting\/suppliers\/names","methods":["GET","HEAD"]},"api.accounting.suppliers.index":{"uri":"api\/accounting\/suppliers","methods":["GET","HEAD"]},"api.accounting.suppliers.create":{"uri":"api\/accounting\/suppliers\/create","methods":["GET","HEAD"]},"api.accounting.suppliers.store":{"uri":"api\/accounting\/suppliers","methods":["POST"]},"api.accounting.suppliers.show":{"uri":"api\/accounting\/suppliers\/{supplier}","methods":["GET","HEAD"],"bindings":{"supplier":"slug"}},"api.accounting.suppliers.edit":{"uri":"api\/accounting\/suppliers\/{supplier}\/edit","methods":["GET","HEAD"]},"api.accounting.suppliers.update":{"uri":"api\/accounting\/suppliers\/{supplier}","methods":["PUT","PATCH"],"bindings":{"supplier":"slug"}},"api.accounting.suppliers.destroy":{"uri":"api\/accounting\/suppliers\/{supplier}","methods":["DELETE"],"bindings":{"supplier":"slug"}},"api.accounting.suppliers.transactions":{"uri":"api\/accounting\/suppliers\/{supplier}\/transactions","methods":["GET","HEAD"],"bindings":{"supplier":"slug"}},"api.accounting.budgets.names":{"uri":"api\/accounting\/budgets\/names","methods":["GET","HEAD"]},"api.accounting.budgets.index":{"uri":"api\/accounting\/budgets","methods":["GET","HEAD"]},"api.accounting.budgets.store":{"uri":"api\/accounting\/budgets","methods":["POST"]},"api.accounting.budgets.show":{"uri":"api\/accounting\/budgets\/{budget}","methods":["GET","HEAD"],"bindings":{"budget":"id"}},"api.accounting.budgets.update":{"uri":"api\/accounting\/budgets\/{budget}","methods":["PUT","PATCH"],"bindings":{"budget":"id"}},"api.accounting.budgets.destroy":{"uri":"api\/accounting\/budgets\/{budget}","methods":["DELETE"],"bindings":{"budget":"id"}},"api.accounting.budgets.transactions":{"uri":"api\/accounting\/budgets\/{budget}\/transactions","methods":["GET","HEAD"],"bindings":{"budget":"id"}},"api.accounting.budgets.donations":{"uri":"api\/accounting\/budgets\/{budget}\/donations","methods":["GET","HEAD"],"bindings":{"budget":"id"}},"api.accounting.budgets.export":{"uri":"api\/accounting\/budgets\/{budget}\/export","methods":["GET","HEAD"],"bindings":{"budget":"id"}},"api.cmtyvol.ageDistribution":{"uri":"api\/cmtyvol\/ageDistribution","methods":["GET","HEAD"]},"api.cmtyvol.nationalityDistribution":{"uri":"api\/cmtyvol\/nationalityDistribution","methods":["GET","HEAD"]},"api.cmtyvol.genderDistribution":{"uri":"api\/cmtyvol\/genderDistribution","methods":["GET","HEAD"]},"api.cmtyvol.getHeaderMappings":{"uri":"api\/cmtyvol\/getHeaderMappings","methods":["POST"]},"api.cmtyvol.index":{"uri":"api\/cmtyvol","methods":["GET","HEAD"]},"api.cmtyvol.show":{"uri":"api\/cmtyvol\/{cmtyvol}","methods":["GET","HEAD"],"bindings":{"cmtyvol":"id"}},"api.cmtyvol.comments.index":{"uri":"api\/cmtyvol\/{cmtyvol}\/comments","methods":["GET","HEAD"],"bindings":{"cmtyvol":"id"}},"api.cmtyvol.comments.store":{"uri":"api\/cmtyvol\/{cmtyvol}\/comments","methods":["POST"],"bindings":{"cmtyvol":"id"}},"api.kb.articles.index":{"uri":"api\/kb\/articles","methods":["GET","HEAD"]},"api.kb.articles.create":{"uri":"api\/kb\/articles\/create","methods":["GET","HEAD"]},"api.kb.articles.store":{"uri":"api\/kb\/articles","methods":["POST"]},"api.kb.articles.show":{"uri":"api\/kb\/articles\/{article}","methods":["GET","HEAD"],"bindings":{"article":"slug"}},"api.kb.articles.edit":{"uri":"api\/kb\/articles\/{article}\/edit","methods":["GET","HEAD"]},"api.kb.articles.update":{"uri":"api\/kb\/articles\/{article}","methods":["PUT","PATCH"],"bindings":{"article":"slug"}},"api.kb.articles.destroy":{"uri":"api\/kb\/articles\/{article}","methods":["DELETE"],"bindings":{"article":"slug"}},"api.visitors.listCurrent":{"uri":"api\/visitors\/current","methods":["GET","HEAD"]},"api.visitors.checkin":{"uri":"api\/visitors\/checkin","methods":["POST"]},"api.visitors.checkout":{"uri":"api\/visitors\/{visitor}\/checkout","methods":["PUT"],"bindings":{"visitor":"id"}},"api.visitors.checkoutAll":{"uri":"api\/visitors\/checkoutAll","methods":["POST"]},"api.visitors.export":{"uri":"api\/visitors\/export","methods":["GET","HEAD"]},"api.visitors.dailyVisitors":{"uri":"api\/visitors\/dailyVisitors","methods":["GET","HEAD"]},"api.visitors.monthlyVisitors":{"uri":"api\/visitors\/monthlyVisitors","methods":["GET","HEAD"]},"api.countries":{"uri":"api\/countries","methods":["GET","HEAD"]},"api.languages":{"uri":"api\/languages","methods":["GET","HEAD"]},"api.settings":{"uri":"api\/settings","methods":["GET","HEAD"]},"accounting.webling.index":{"uri":"accounting\/wallets\/{wallet}\/webling","methods":["GET","HEAD"],"bindings":{"wallet":"id"}}}};

if (typeof window !== 'undefined' && typeof window.Ziggy !== 'undefined') {
    Object.assign(Ziggy.routes, window.Ziggy.routes);
}

export { Ziggy };
