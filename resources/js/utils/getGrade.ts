export function getGrade(grade: number, year: string){
    const now = new Date();
    const yearDiff = now.getFullYear() - +year;

    console.log(`Year: ${yearDiff}, Grade: ${grade}`);

    if(grade - yearDiff > 0){
        return grade - yearDiff;
    }

    return false;
}
