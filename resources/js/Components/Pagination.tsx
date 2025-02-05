import PrimaryButton from "@/Components/PrimaryButton";
import { FaAnglesLeft, FaAnglesRight } from "react-icons/fa6";

export default function Pagination({ length, page, setPage, perPage } : {
    length: number,
    page: number,
    perPage: number,
    setPage: any
}){
    const prevPage = () => {
        if((page - 1) <= 0) return;

        setPage(page-1);
    }

    const nextPage = () => {
        if((page + 1) > Math.ceil(length/10)) return;

        setPage(page+1);
    }
    return (
            <div className="py-4 text-center flex justify-between items-center">
                <p className="font-light text-gray-500 italic">Showing page {page} of {Math.ceil(length/perPage)}</p>
                <div className="flex gap-4">
                    <PrimaryButton onClick={prevPage}>
                        <FaAnglesLeft />
                    </PrimaryButton>
                    {
                        (() => {
                            return Array.from(
                            { length: Math.ceil(length/perPage) },
                            (_, i) => (
                                <p onClick={() => setPage(i+1)} className={`hover:underline ${((i + 1) == page) && 'underline text-blue-700'}`} key={i}>{i+1}</p>
                            )
                        )})()
                    }
                    <PrimaryButton onClick={nextPage}>
                        <FaAnglesRight />
                    </PrimaryButton>
                </div>
            </div>

    );
}
