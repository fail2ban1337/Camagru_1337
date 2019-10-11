function showtog()
{
    var element = document.getElementById("navbarsExampleDefault");
    if (element.classList.contains('collapse.show'))
    {
        element.className = element.className.replace(/\bcollapse.show navbar-collapse\b/g,"collapse navbar-collapse");
    }else {
        element.className = element.className.replace(/\bcollapse navbar-collapse\b/g,"collapse.show navbar-collapse");
    }
}