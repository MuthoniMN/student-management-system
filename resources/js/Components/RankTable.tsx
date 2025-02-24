import { useEffect, useState } from "react";
import Pagination from "@/Components/Pagination";
import { TRankResult } from "@/types/";

export default function RankTable({ results } : { results: TRankResult[] }){
    const [page, setPage] = useState(1);
    const [data, setData] = useState(results.sort((a,b) => b.total - a.total));
    const perPage = 10;
    const start = (page - 1) * perPage;
    const end = start + perPage;
    const [paginatedData, setPaginatedData] = useState(data.slice(start, end));

    useEffect(() => {
            setPaginatedData(data.slice(start,end));
    }, [page, data]);

    console.log(data);

    return (
        <section className="p-4">
            <table className="w-full text-left border-[2px] border-gray-500 divide-y-[2px] divide-gray-300">
                <thead>
                    <tr className="divide-x-[2px] divide-gray-300">
                        <th className="px-2">Rank</th>
                        <th className="px-2">Student ID</th>
                        <th className="px-2">Student Name</th>
                        <th className="px-2">Marks</th>
                    </tr>
                </thead>
                <tbody className="divide-y-[2px] divide-gray-300">
                    {
                        paginatedData.length > 0 ? paginatedData.map((res, id) => (
                            <tr className="divide-x-[2px] divide-gray-300 py-[2px]" key={res.id}>
                                <td className="px-2">{id + 1}</td>
                                <td className="px-2">{res.id}</td>
                                <td className="px-2">{res.name}</td>
                                <td className="px-2">{res.total}</td>
                            </tr>
                        )) : <tr className="text-center">
                            <td colSpan={4}>No Results Available</td>
                        </tr>
                    }
                </tbody>
            </table>
            <Pagination page={page} setPage={setPage} perPage={perPage} length={data.length} />
        </section>
    )
}
