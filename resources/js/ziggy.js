const Ziggy = {"url":"https:\/\/ohf.test","port":null,"defaults":{},"routes":{"api.users.index":{"uri":"api\/users","methods":["GET","HEAD"]},"api.users.store":{"uri":"api\/users","methods":["POST"]},"api.users.show":{"uri":"api\/users\/{user}","methods":["GET","HEAD"],"bindings":{"user":"id"}},"api.users.update":{"uri":"api\/users\/{user}","methods":["PUT","PATCH"],"bindings":{"user":"id"}},"api.users.destroy":{"uri":"api\/users\/{user}","methods":["DELETE"],"bindings":{"user":"id"}},"api.users.roles.index":{"uri":"api\/users\/{user}\/roles","methods":["GET","HEAD"],"bindings":{"user":"id"}},"api.users.relationships.roles.index":{"uri":"api\/users\/{user}\/relationships\/roles","methods":["GET","HEAD"],"bindings":{"user":"id"}},"api.users.relationships.roles.store":{"uri":"api\/users\/{user}\/relationships\/roles","methods":["POST"],"bindings":{"user":"id"}},"api.users.relationships.roles.update":{"uri":"api\/users\/{user}\/relationships\/roles","methods":["PUT"],"bindings":{"user":"id"}},"api.users.relationships.roles.destroy":{"uri":"api\/users\/{user}\/relationships\/roles","methods":["DELETE"],"bindings":{"user":"id"}},"api.roles.index":{"uri":"api\/roles","methods":["GET","HEAD"]},"api.roles.store":{"uri":"api\/roles","methods":["POST"]},"api.roles.show":{"uri":"api\/roles\/{role}","methods":["GET","HEAD"],"bindings":{"role":"id"}},"api.roles.update":{"uri":"api\/roles\/{role}","methods":["PUT","PATCH"],"bindings":{"role":"id"}},"api.roles.destroy":{"uri":"api\/roles\/{role}","methods":["DELETE"],"bindings":{"role":"id"}},"api.roles.users.index":{"uri":"api\/roles\/{role}\/users","methods":["GET","HEAD"],"bindings":{"role":"id"}},"api.roles.administrators.index":{"uri":"api\/roles\/{role}\/administrators","methods":["GET","HEAD"],"bindings":{"role":"id"}},"api.roles.relationships.users.index":{"uri":"api\/roles\/{role}\/relationships\/users","methods":["GET","HEAD"],"bindings":{"role":"id"}},"api.roles.relationships.users.store":{"uri":"api\/roles\/{role}\/relationships\/users","methods":["POST"],"bindings":{"role":"id"}},"api.roles.relationships.users.update":{"uri":"api\/roles\/{role}\/relationships\/users","methods":["PUT"],"bindings":{"role":"id"}},"api.roles.relationships.users.destroy":{"uri":"api\/roles\/{role}\/relationships\/users","methods":["DELETE"],"bindings":{"role":"id"}},"api.roles.relationships.administrators.index":{"uri":"api\/roles\/{role}\/relationships\/administrators","methods":["GET","HEAD"],"bindings":{"role":"id"}},"api.roles.relationships.administrators.store":{"uri":"api\/roles\/{role}\/relationships\/administrators","methods":["POST"],"bindings":{"role":"id"}},"api.roles.relationships.administrators.update":{"uri":"api\/roles\/{role}\/relationships\/administrators","methods":["PUT"],"bindings":{"role":"id"}},"api.roles.relationships.administrators.destroy":{"uri":"api\/roles\/{role}\/relationships\/administrators","methods":["DELETE"],"bindings":{"role":"id"}},"api.fundraising.donors.export":{"uri":"api\/fundraising\/donors\/export","methods":["GET","HEAD"]},"api.fundraising.donors.salutations":{"uri":"api\/fundraising\/donors\/salutations","methods":["GET","HEAD"]},"api.fundraising.donors.index":{"uri":"api\/fundraising\/donors","methods":["GET","HEAD"]},"api.fundraising.donors.store":{"uri":"api\/fundraising\/donors","methods":["POST"]},"api.fundraising.donors.show":{"uri":"api\/fundraising\/donors\/{donor}","methods":["GET","HEAD"],"bindings":{"donor":"id"}},"api.fundraising.donors.update":{"uri":"api\/fundraising\/donors\/{donor}","methods":["PUT","PATCH"],"bindings":{"donor":"id"}},"api.fundraising.donors.destroy":{"uri":"api\/fundraising\/donors\/{donor}","methods":["DELETE"],"bindings":{"donor":"id"}},"api.fundraising.donors.vcard":{"uri":"api\/fundraising\/donors\/{donor}\/vcard","methods":["GET","HEAD"],"bindings":{"donor":"id"}},"api.fundraising.donors.donations.index":{"uri":"api\/fundraising\/donors\/{donor}\/donations","methods":["GET","HEAD"],"bindings":{"donor":"id"}},"api.fundraising.donors.donations.store":{"uri":"api\/fundraising\/donors\/{donor}\/donations","methods":["POST"],"bindings":{"donor":"id"}},"api.fundraising.donors.donations.export":{"uri":"api\/fundraising\/donors\/{donor}\/donations\/export","methods":["GET","HEAD"],"bindings":{"donor":"id"}},"api.fundraising.donors.comments.index":{"uri":"api\/fundraising\/donors\/{donor}\/comments","methods":["GET","HEAD"],"bindings":{"donor":"id"}},"api.fundraising.donors.comments.store":{"uri":"api\/fundraising\/donors\/{donor}\/comments","methods":["POST"],"bindings":{"donor":"id"}},"api.fundraising.donors.tags.index":{"uri":"api\/fundraising\/donors\/{donor}\/tags","methods":["GET","HEAD"],"bindings":{"donor":"id"}},"api.fundraising.donors.tags.store":{"uri":"api\/fundraising\/donors\/{donor}\/tags","methods":["POST"],"bindings":{"donor":"id"}},"api.fundraising.tags.index":{"uri":"api\/fundraising\/tags","methods":["GET","HEAD"]},"api.fundraising.donations.channels":{"uri":"api\/fundraising\/donations\/channels","methods":["GET","HEAD"]},"api.fundraising.donations.currencies":{"uri":"api\/fundraising\/donations\/currencies","methods":["GET","HEAD"]},"api.fundraising.donations.export":{"uri":"api\/fundraising\/donations\/export","methods":["GET","HEAD"]},"api.fundraising.donations.import":{"uri":"api\/fundraising\/donations\/import","methods":["POST"]},"api.fundraising.donations.index":{"uri":"api\/fundraising\/donations","methods":["GET","HEAD"]},"api.fundraising.donations.store":{"uri":"api\/fundraising\/donations","methods":["POST"]},"api.fundraising.donations.show":{"uri":"api\/fundraising\/donations\/{donation}","methods":["GET","HEAD"],"bindings":{"donation":"id"}},"api.fundraising.donations.update":{"uri":"api\/fundraising\/donations\/{donation}","methods":["PUT","PATCH"],"bindings":{"donation":"id"}},"api.fundraising.donations.destroy":{"uri":"api\/fundraising\/donations\/{donation}","methods":["DELETE"],"bindings":{"donation":"id"}},"api.fundraising.report.donors.count":{"uri":"api\/fundraising\/report\/donors\/count","methods":["GET","HEAD"]},"api.fundraising.report.donors.languages":{"uri":"api\/fundraising\/report\/donors\/languages","methods":["GET","HEAD"]},"api.fundraising.report.donors.countries":{"uri":"api\/fundraising\/report\/donors\/countries","methods":["GET","HEAD"]},"api.fundraising.report.donors.registrations":{"uri":"api\/fundraising\/report\/donors\/registrations","methods":["GET","HEAD"]},"api.fundraising.report.donations.registrations":{"uri":"api\/fundraising\/report\/donations\/registrations","methods":["GET","HEAD"]},"api.fundraising.report.donations.currencies":{"uri":"api\/fundraising\/report\/donations\/currencies","methods":["GET","HEAD"]},"api.fundraising.report.donations.channels":{"uri":"api\/fundraising\/report\/donations\/channels","methods":["GET","HEAD"]},"api.comments.show":{"uri":"api\/comments\/{comment}","methods":["GET","HEAD"],"bindings":{"comment":"id"}},"api.comments.update":{"uri":"api\/comments\/{comment}","methods":["PUT","PATCH"],"bindings":{"comment":"id"}},"api.comments.destroy":{"uri":"api\/comments\/{comment}","methods":["DELETE"],"bindings":{"comment":"id"}},"api.accounting.wallets.index":{"uri":"api\/accounting\/wallets","methods":["GET","HEAD"]},"api.accounting.wallets.create":{"uri":"api\/accounting\/wallets\/create","methods":["GET","HEAD"]},"api.accounting.wallets.store":{"uri":"api\/accounting\/wallets","methods":["POST"]},"api.accounting.wallets.show":{"uri":"api\/accounting\/wallets\/{wallet}","methods":["GET","HEAD"],"bindings":{"wallet":"id"}},"api.accounting.wallets.edit":{"uri":"api\/accounting\/wallets\/{wallet}\/edit","methods":["GET","HEAD"]},"api.accounting.wallets.update":{"uri":"api\/accounting\/wallets\/{wallet}","methods":["PUT","PATCH"],"bindings":{"wallet":"id"}},"api.accounting.wallets.destroy":{"uri":"api\/accounting\/wallets\/{wallet}","methods":["DELETE"],"bindings":{"wallet":"id"}},"api.accounting.transactions.updateReceipt":{"uri":"api\/accounting\/transactions\/{transaction}\/receipt","methods":["POST"],"bindings":{"transaction":"id"}},"api.accounting.transactions.controlled":{"uri":"api\/accounting\/transactions\/{transaction}\/controlled","methods":["GET","HEAD"],"bindings":{"transaction":"id"}},"api.accounting.transactions.markControlled":{"uri":"api\/accounting\/transactions\/{transaction}\/controlled","methods":["POST"],"bindings":{"transaction":"id"}},"api.accounting.transactions.undoControlled":{"uri":"api\/accounting\/transactions\/{transaction}\/controlled","methods":["DELETE"],"bindings":{"transaction":"id"}},"api.accounting.suppliers.export":{"uri":"api\/accounting\/suppliers\/export","methods":["GET","HEAD"]},"api.accounting.suppliers.index":{"uri":"api\/accounting\/suppliers","methods":["GET","HEAD"]},"api.accounting.suppliers.create":{"uri":"api\/accounting\/suppliers\/create","methods":["GET","HEAD"]},"api.accounting.suppliers.store":{"uri":"api\/accounting\/suppliers","methods":["POST"]},"api.accounting.suppliers.show":{"uri":"api\/accounting\/suppliers\/{supplier}","methods":["GET","HEAD"],"bindings":{"supplier":"slug"}},"api.accounting.suppliers.edit":{"uri":"api\/accounting\/suppliers\/{supplier}\/edit","methods":["GET","HEAD"]},"api.accounting.suppliers.update":{"uri":"api\/accounting\/suppliers\/{supplier}","methods":["PUT","PATCH"],"bindings":{"supplier":"slug"}},"api.accounting.suppliers.destroy":{"uri":"api\/accounting\/suppliers\/{supplier}","methods":["DELETE"],"bindings":{"supplier":"slug"}},"api.accounting.suppliers.transactions":{"uri":"api\/accounting\/suppliers\/{supplier}\/transactions","methods":["GET","HEAD"],"bindings":{"supplier":"slug"}},"api.people.index":{"uri":"api\/people","methods":["GET","HEAD"]},"api.people.filterPersons":{"uri":"api\/people\/filterPersons","methods":["GET","HEAD"]},"api.people.store":{"uri":"api\/people","methods":["POST"]},"api.people.show":{"uri":"api\/people\/{person}","methods":["GET","HEAD"],"bindings":{"person":"public_id"}},"api.people.update":{"uri":"api\/people\/{person}","methods":["PUT"],"bindings":{"person":"public_id"}},"api.people.updateGender":{"uri":"api\/people\/{person}\/gender","methods":["PATCH"],"bindings":{"person":"public_id"}},"api.people.updateDateOfBirth":{"uri":"api\/people\/{person}\/date_of_birth","methods":["PATCH"],"bindings":{"person":"public_id"}},"api.people.updateNationality":{"uri":"api\/people\/{person}\/nationality","methods":["PATCH"],"bindings":{"person":"public_id"}},"api.people.updatePoliceNo":{"uri":"api\/people\/{person}\/updatePoliceNo","methods":["PATCH"],"bindings":{"person":"public_id"}},"api.people.updateRemarks":{"uri":"api\/people\/{person}\/remarks","methods":["PATCH"],"bindings":{"person":"public_id"}},"api.people.registerCard":{"uri":"api\/people\/{person}\/card","methods":["PATCH"],"bindings":{"person":"public_id"}},"api.people.reporting.numbers":{"uri":"api\/people\/reporting\/numbers","methods":["GET","HEAD"]},"api.people.reporting.nationalities":{"uri":"api\/people\/reporting\/nationalities","methods":["GET","HEAD"]},"api.people.reporting.genderDistribution":{"uri":"api\/people\/reporting\/genderDistribution","methods":["GET","HEAD"]},"api.people.reporting.ageDistribution":{"uri":"api\/people\/reporting\/ageDistribution","methods":["GET","HEAD"]},"api.people.reporting.registrationsPerDay":{"uri":"api\/people\/reporting\/registrationsPerDay","methods":["GET","HEAD"]},"api.people.reporting.monthlySummary":{"uri":"api\/people\/reporting\/monthlySummary","methods":["GET","HEAD"]},"api.bank.withdrawal.dailyStats":{"uri":"api\/bank\/withdrawal\/dailyStats","methods":["GET","HEAD"]},"api.bank.withdrawal.transactions":{"uri":"api\/bank\/withdrawal\/transactions","methods":["GET","HEAD"]},"api.bank.withdrawal.search":{"uri":"api\/bank\/withdrawal\/search","methods":["GET","HEAD"]},"api.bank.withdrawal.person":{"uri":"api\/bank\/withdrawal\/persons\/{person}","methods":["GET","HEAD"],"bindings":{"person":"public_id"}},"api.bank.withdrawal.handoutCoupon":{"uri":"api\/bank\/person\/{person}\/couponType\/{couponType}\/handout","methods":["POST"],"bindings":{"person":"public_id","couponType":"id"}},"api.bank.withdrawal.undoHandoutCoupon":{"uri":"api\/bank\/person\/{person}\/couponType\/{couponType}\/handout","methods":["DELETE"],"bindings":{"person":"public_id","couponType":"id"}},"api.bank.reporting.withdrawals":{"uri":"api\/bank\/withdrawals","methods":["GET","HEAD"]},"api.bank.reporting.couponsHandedOutPerDay":{"uri":"api\/bank\/withdrawals\/chart\/couponsHandedOutPerDay\/{coupon}","methods":["GET","HEAD"],"bindings":{"coupon":"id"}},"api.bank.reporting.visitors":{"uri":"api\/bank\/visitors","methods":["GET","HEAD"]},"api.bank.reporting.visitorsPerDay":{"uri":"api\/bank\/visitors\/chart\/visitorsPerDay","methods":["GET","HEAD"]},"api.bank.reporting.visitorsPerWeek":{"uri":"api\/bank\/visitors\/chart\/visitorsPerWeek","methods":["GET","HEAD"]},"api.bank.reporting.visitorsPerMonth":{"uri":"api\/bank\/visitors\/chart\/visitorsPerMonth","methods":["GET","HEAD"]},"api.bank.reporting.visitorsPerYear":{"uri":"api\/bank\/visitors\/chart\/visitorsPerYear","methods":["GET","HEAD"]},"api.bank.reporting.avgVisitorsPerDayOfWeek":{"uri":"api\/bank\/visitors\/chart\/avgVisitorsPerDayOfWeek","methods":["GET","HEAD"]},"api.cmtyvol.ageDistribution":{"uri":"api\/cmtyvol\/ageDistribution","methods":["GET","HEAD"]},"api.cmtyvol.nationalityDistribution":{"uri":"api\/cmtyvol\/nationalityDistribution","methods":["GET","HEAD"]},"api.cmtyvol.genderDistribution":{"uri":"api\/cmtyvol\/genderDistribution","methods":["GET","HEAD"]},"api.cmtyvol.getHeaderMappings":{"uri":"api\/cmtyvol\/getHeaderMappings","methods":["POST"]},"api.cmtyvol.index":{"uri":"api\/cmtyvol","methods":["GET","HEAD"]},"api.cmtyvol.show":{"uri":"api\/cmtyvol\/{cmtyvol}","methods":["GET","HEAD"],"bindings":{"cmtyvol":"id"}},"api.cmtyvol.comments.index":{"uri":"api\/cmtyvol\/{cmtyvol}\/comments","methods":["GET","HEAD"],"bindings":{"cmtyvol":"id"}},"api.cmtyvol.comments.store":{"uri":"api\/cmtyvol\/{cmtyvol}\/comments","methods":["POST"],"bindings":{"cmtyvol":"id"}},"api.shop.cards.listRedeemedToday":{"uri":"api\/shop\/cards\/listRedeemedToday","methods":["GET","HEAD"]},"api.shop.cards.searchByCode":{"uri":"api\/shop\/cards\/searchByCode","methods":["GET","HEAD"]},"api.shop.cards.redeem":{"uri":"api\/shop\/cards\/redeem\/{handout}","methods":["PATCH"],"bindings":{"handout":"id"}},"api.shop.cards.cancel":{"uri":"api\/shop\/cards\/cancel\/{handout}","methods":["DELETE"],"bindings":{"handout":"id"}},"api.shop.cards.listNonRedeemedByDay":{"uri":"api\/shop\/cards\/listNonRedeemedByDay","methods":["GET","HEAD"]},"api.shop.cards.deleteNonRedeemedByDay":{"uri":"api\/shop\/cards\/deleteNonRedeemedByDay","methods":["POST"]},"api.library.lending.stats":{"uri":"api\/library\/lending\/stats","methods":["GET","HEAD"]},"api.library.lending.persons":{"uri":"api\/library\/lending\/persons","methods":["GET","HEAD"]},"api.library.lending.books":{"uri":"api\/library\/lending\/books","methods":["GET","HEAD"]},"api.library.lending.person":{"uri":"api\/library\/lending\/person\/{person}","methods":["GET","HEAD"],"bindings":{"person":"public_id"}},"api.library.lending.lendBookToPerson":{"uri":"api\/library\/lending\/person\/{person}\/lendBook","methods":["POST"],"bindings":{"person":"public_id"}},"api.library.lending.extendBookToPerson":{"uri":"api\/library\/lending\/person\/{person}\/extendBook","methods":["POST"],"bindings":{"person":"public_id"}},"api.library.lending.returnBookFromPerson":{"uri":"api\/library\/lending\/person\/{person}\/returnBook","methods":["POST"],"bindings":{"person":"public_id"}},"api.library.lending.personLog":{"uri":"api\/library\/lending\/person\/{person}\/log","methods":["GET","HEAD"],"bindings":{"person":"public_id"}},"api.library.lending.book":{"uri":"api\/library\/lending\/book\/{book}","methods":["GET","HEAD"],"bindings":{"book":"id"}},"api.library.lending.lendBook":{"uri":"api\/library\/lending\/book\/{book}\/lend","methods":["POST"],"bindings":{"book":"id"}},"api.library.lending.extendBook":{"uri":"api\/library\/lending\/book\/{book}\/extend","methods":["POST"],"bindings":{"book":"id"}},"api.library.lending.returnBook":{"uri":"api\/library\/lending\/book\/{book}\/return","methods":["POST"],"bindings":{"book":"id"}},"api.library.lending.bookLog":{"uri":"api\/library\/lending\/book\/{book}\/log","methods":["GET","HEAD"],"bindings":{"book":"id"}},"api.library.books.filter":{"uri":"api\/library\/books\/filter","methods":["GET","HEAD"]},"api.library.books.findIsbn":{"uri":"api\/library\/books\/findIsbn","methods":["GET","HEAD"]},"api.library.books.index":{"uri":"api\/library\/books","methods":["GET","HEAD"]},"api.library.books.store":{"uri":"api\/library\/books","methods":["POST"]},"api.library.books.show":{"uri":"api\/library\/books\/{book}","methods":["GET","HEAD"],"bindings":{"book":"id"}},"api.library.books.update":{"uri":"api\/library\/books\/{book}","methods":["PUT","PATCH"],"bindings":{"book":"id"}},"api.library.books.destroy":{"uri":"api\/library\/books\/{book}","methods":["DELETE"],"bindings":{"book":"id"}},"api.library.export":{"uri":"api\/library\/export","methods":["GET","HEAD"]},"api.library.doExport":{"uri":"api\/library\/export","methods":["POST"]},"api.library.report":{"uri":"api\/library\/report","methods":["GET","HEAD"]},"api.kb.articles.index":{"uri":"api\/kb\/articles","methods":["GET","HEAD"]},"api.kb.articles.create":{"uri":"api\/kb\/articles\/create","methods":["GET","HEAD"]},"api.kb.articles.store":{"uri":"api\/kb\/articles","methods":["POST"]},"api.kb.articles.show":{"uri":"api\/kb\/articles\/{article}","methods":["GET","HEAD"],"bindings":{"article":"slug"}},"api.kb.articles.edit":{"uri":"api\/kb\/articles\/{article}\/edit","methods":["GET","HEAD"]},"api.kb.articles.update":{"uri":"api\/kb\/articles\/{article}","methods":["PUT","PATCH"],"bindings":{"article":"slug"}},"api.kb.articles.destroy":{"uri":"api\/kb\/articles\/{article}","methods":["DELETE"],"bindings":{"article":"slug"}},"api.visitors.listCurrent":{"uri":"api\/visitors\/current","methods":["GET","HEAD"]},"api.visitors.checkin":{"uri":"api\/visitors\/checkin","methods":["POST"]},"api.visitors.checkout":{"uri":"api\/visitors\/{visitor}\/checkout","methods":["PUT"],"bindings":{"visitor":"id"}},"api.visitors.checkoutAll":{"uri":"api\/visitors\/checkoutAll","methods":["POST"]},"api.visitors.export":{"uri":"api\/visitors\/export","methods":["GET","HEAD"]},"api.visitors.dailyVisitors":{"uri":"api\/visitors\/dailyVisitors","methods":["GET","HEAD"]},"api.visitors.monthlyVisitors":{"uri":"api\/visitors\/monthlyVisitors","methods":["GET","HEAD"]},"api.countries":{"uri":"api\/countries","methods":["GET","HEAD"]},"api.languages":{"uri":"api\/languages","methods":["GET","HEAD"]},"users.avatar":{"uri":"users\/{user}\/avatar","methods":["GET","HEAD"]},"accounting.transactions.index":{"uri":"accounting\/wallets\/{wallet}\/transactions","methods":["GET","HEAD"],"bindings":{"wallet":"id"}},"accounting.transactions.show":{"uri":"accounting\/transactions\/{transaction}","methods":["GET","HEAD"],"bindings":{"transaction":"id"}},"people.bulkSearch":{"uri":"people\/bulkSearch","methods":["GET","HEAD"]},"people.doBulkSearch":{"uri":"people\/bulkSearch","methods":["POST"]},"people.export":{"uri":"people\/export","methods":["GET","HEAD"]},"people.import":{"uri":"people\/import","methods":["GET","HEAD"]},"people.doImport":{"uri":"people\/doImport","methods":["POST"]},"people.duplicates":{"uri":"people\/duplicates","methods":["GET","HEAD"]},"people.applyDuplicates":{"uri":"people\/duplicates","methods":["POST"]},"people.index":{"uri":"people","methods":["GET","HEAD"]},"people.create":{"uri":"people\/create","methods":["GET","HEAD"]},"people.store":{"uri":"people","methods":["POST"]},"people.show":{"uri":"people\/{person}","methods":["GET","HEAD"],"bindings":{"person":"public_id"}},"people.edit":{"uri":"people\/{person}\/edit","methods":["GET","HEAD"],"bindings":{"person":"public_id"}},"people.update":{"uri":"people\/{person}","methods":["PUT","PATCH"],"bindings":{"person":"public_id"}},"people.destroy":{"uri":"people\/{person}","methods":["DELETE"],"bindings":{"person":"public_id"}}}};

if (typeof window !== 'undefined' && typeof window.Ziggy !== 'undefined') {
    for (let name in window.Ziggy.routes) {
        Ziggy.routes[name] = window.Ziggy.routes[name];
    }
}

export { Ziggy };
